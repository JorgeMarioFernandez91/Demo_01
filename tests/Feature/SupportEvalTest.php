<?php

use App\Support\TicketClassifier;
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
 * A Messages API response where Claude calls the category tool.
 *
 * @return array<string, mixed>
 */
function categoryResponse(string $category): array
{
    return [
        'stop_reason' => 'tool_use',
        'content' => [
            ['type' => 'tool_use', 'id' => 'toolu_1', 'name' => TicketClassifier::TOOL, 'input' => ['category' => $category]],
        ],
    ];
}

it('categorizes a ticket with a strict tool and the configured model', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(categoryResponse('bug'))]);

    expect(app(TicketClassifier::class)->classify('Export broken', 'The export button spins forever.'))->toBe('bug');

    Http::assertSent(fn (Request $request) => $request->url() === 'https://api.anthropic.com/v1/messages'
        && $request->hasHeader('x-api-key', 'test-key')
        && $request['model'] === 'claude-test-model'
        && $request['tools'][0]['name'] === TicketClassifier::TOOL
        && $request['tools'][0]['strict'] === true
        && $request['tools'][0]['input_schema']['properties']['category']['enum'] === ['billing', 'account', 'bug', 'feature-request']
        && str_contains($request['messages'][0]['content'], 'The export button spins forever.'));
});

it('fails when Claude does not call the tool', function () {
    Http::fake(['api.anthropic.com/*' => Http::response([
        'stop_reason' => 'end_turn',
        'content' => [['type' => 'text', 'text' => 'Probably billing.']],
    ])]);

    app(TicketClassifier::class)->classify('Subject', 'Body');
})->throws(RuntimeException::class, 'did not return a category');

it('fails when Claude refuses', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(['stop_reason' => 'refusal', 'content' => []])]);

    app(TicketClassifier::class)->classify('Subject', 'Body');
})->throws(RuntimeException::class, 'declined');

it('prints accuracy and the tickets it got wrong', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(categoryResponse('billing'))]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('Accuracy: 4/15 (26.7%)')
        ->expectsOutputToContain('Magic link not working')
        ->expectsOutputToContain('Invoices in euros')
        ->assertSuccessful();

    Http::assertSentCount(15);
});

it('counts API errors as wrong answers', function () {
    Http::fake(['api.anthropic.com/*' => Http::response(['type' => 'error'], 500)]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('Accuracy: 0/15 (0.0%)')
        ->expectsOutputToContain('error:')
        ->assertSuccessful();
});

it('stops without an API key', function () {
    config(['services.anthropic.key' => null]);

    $this->artisan('support:eval')
        ->expectsOutputToContain('ANTHROPIC_API_KEY is not set.')
        ->assertFailed();

    Http::assertNothingSent();
});
