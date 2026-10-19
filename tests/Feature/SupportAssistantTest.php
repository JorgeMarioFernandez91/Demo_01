<?php

use Anthropic\Client;
use App\Models\HelpArticle;
use App\Models\Tenant;
use App\Support\SupportAssistant;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Bind a SupportAssistant whose HTTP client answers every request with the
 * given output, and record the request bodies sent to the API.
 */
function fakeAssistant(array $output, string $stopReason = 'end_turn'): ArrayObject
{
    $requests = new ArrayObject;

    $http = new Guzzle(['handler' => function (RequestInterface $request) use ($requests, $output, $stopReason) {
        $requests[] = json_decode((string) $request->getBody(), true);

        return Create::promiseFor(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'id' => 'msg_test',
            'type' => 'message',
            'role' => 'assistant',
            'model' => 'claude-opus-5-5',
            'content' => [['type' => 'text', 'text' => json_encode($output)]],
            'stop_reason' => $stopReason,
            'stop_sequence' => null,
            'usage' => ['input_tokens' => 1, 'output_tokens' => 1],
        ])));
    }]);

    app()->instance(SupportAssistant::class, new SupportAssistant(
        new Client(apiKey: 'test-key', requestOptions: ['transporter' => $http, 'maxRetries' => 0]),
        'claude-opus-5-5',
        'medium',
    ));

    return $requests;
}

beforeEach(function () {
    $this->acme = Tenant::factory()->create(['slug' => 'acme']);
    $this->globex = Tenant::factory()->create(['slug' => 'globex']);

    asTenant($this->acme, fn () => HelpArticle::factory()->create(['title' => 'Acme refunds guide']));
    asTenant($this->globex, fn () => HelpArticle::factory()->create(['title' => 'Globex discount codes']));
});

it('returns the category and draft reply', function () {
    $requests = fakeAssistant(['category' => 'billing', 'reply' => 'Here is how refunds work.']);

    $result = asTenant($this->acme, fn () => app(SupportAssistant::class)
        ->triage('Refund please', 'I was charged twice.', HelpArticle::all()));

    expect($result)->toBe(['category' => 'billing', 'reply' => 'Here is how refunds work.'])
        ->and($requests[0]['output_config']['format']['schema']['properties']['category']['enum'])
        ->toBe(['billing', 'account', 'bug', 'feature-request']);
});

it('throws when the model refuses', function () {
    fakeAssistant(['category' => 'billing', 'reply' => ''], stopReason: 'refusal');

    app(SupportAssistant::class)->triage('Subject', 'Body', collect());
})->throws(RuntimeException::class, 'stopped early (refusal)');

it('throws on a category outside the allowed list', function () {
    fakeAssistant(['category' => 'sales', 'reply' => 'Hi']);

    app(SupportAssistant::class)->triage('Subject', 'Body', collect());
})->throws(RuntimeException::class, 'invalid response');

it('only sends the current tenant\'s help articles', function () {
    $requests = fakeAssistant(['category' => 'billing', 'reply' => 'Hi']);

    asTenant($this->acme, fn () => app(SupportAssistant::class)->triage('Subject', 'Body', HelpArticle::all()));

    $prompt = $requests[0]['messages'][0]['content'];

    expect($prompt)->toContain('Acme refunds guide')
        ->not->toContain('Globex discount codes');
});

it('runs each eval sample as its own tenant and prints accuracy', function () {
    $requests = fakeAssistant(['category' => 'billing', 'reply' => 'Hi']);
    $samples = require resource_path('evals/support-assistant.php');

    $this->artisan('assistant:eval')
        ->expectsOutputToContain('Category accuracy: 4/15 (27%)')
        ->assertSuccessful();

    expect($requests)->toHaveCount(15);

    foreach ($samples as $i => $sample) {
        [$own, $other] = $sample['tenant'] === 'acme'
            ? ['Acme refunds guide', 'Globex discount codes']
            : ['Globex discount codes', 'Acme refunds guide'];

        expect($requests[$i]['messages'][0]['content'])->toContain($own)->not->toContain($other);
    }
});
