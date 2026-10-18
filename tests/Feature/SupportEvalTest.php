<?php

use App\Support\TicketCategorizer;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config([
        'services.anthropic.key' => 'test-key',
        'services.anthropic.model' => 'claude-test-model',
    ]);

    Http::preventStrayRequests();
});

/**
 * A Messages API response in which Claude calls the tagging tool.
 *
 * @return array<string, mixed>
 */
function taggedResponse(string $category): array
{
    return [
        'type' => 'message',
        'role' => 'assistant',
        'stop_reason' => 'tool_use',
        'content' => [
            ['type' => 'tool_use', 'id' => 'toolu_1', 'name' => TicketCategorizer::TOOL, 'input' => ['category' => $category]],
        ],
    ];
}

it('tags a ticket through a strict tool call using the configured model', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(taggedResponse('bug'))]);

    $category = app(TicketCategorizer::class)->categorize('Export broken', 'The export button spins forever.');

    expect($category)->toBe('bug');

    Http::assertSent(function (Request $request) {
        $tool = $request['tools'][0];

        return $request->url() === 'https://api.anthropic.com/v1/messages'
            && $request->hasHeader('x-api-key', 'test-key')
            && $request['model'] === 'claude-test-model'
            && $request['tool_choice'] === ['type' => 'auto']
            && $tool['name'] === TicketCategorizer::TOOL
            && $tool['strict'] === true
            && $tool['input_schema']['properties']['category']['enum'] === ['billing', 'account', 'bug', 'feature-request']
            && str_contains($request['messages'][0]['content'], 'The export button spins forever.');
    });
});

it('fails when Claude refuses', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(['stop_reason' => 'refusal', 'content' => []])]);

    app(TicketCategorizer::class)->categorize('Subject', 'Body');
})->throws(RuntimeException::class, 'declined');

it('fails when Claude does not call the tool', function () {
    Http::fake(['api.anthropic.com/*' => Http::response([
        'stop_reason' => 'end_turn',
        'content' => [['type' => 'text', 'text' => 'Probably billing.']],
    ])]);

    app(TicketCategorizer::class)->categorize('Subject', 'Body');
})->throws(RuntimeException::class, 'did not return a valid category');

it('reports accuracy and the tickets it got wrong', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(taggedResponse('billing'))]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('Accuracy: 4/15 (27%)')
        ->expectsOutputToContain('Cannot log in after changing my email')
        ->expectsOutputToContain('Pay by bank transfer')
        ->assertSuccessful();

    Http::assertSentCount(15);
});

it('counts API errors as wrong answers', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(['type' => 'error'], 400)]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('Accuracy: 0/15 (0%)')
        ->expectsOutputToContain('error:')
        ->assertSuccessful();
});

it('stops early without an API key', function () {
    config(['services.anthropic.key' => null]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('ANTHROPIC_API_KEY is not set.')
        ->assertFailed();

    Http::assertNothingSent();
});
