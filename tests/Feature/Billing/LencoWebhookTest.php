<?php

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    config([
        'services.lenco.secret_key' => 'sec-test',
        'services.lenco.webhook_secret' => null,
        'services.lenco.api_url' => 'https://lenco.test/access/v2',
    ]);

    Queue::fake();
});

function postWebhook(array $payload, ?string $signature = null)
{
    $body = json_encode($payload);
    $signature ??= hash_hmac('sha512', $body, hash('sha256', 'sec-test'));

    return test()->call('POST', route('webhooks.lenco'), [], [], [], [
        'CONTENT_TYPE' => 'application/json',
        'HTTP_X_LENCO_SIGNATURE' => $signature,
    ], $body);
}

test('a signed collection event confirms the payment with lenco', function () {
    $payment = Payment::factory()->create();
    Http::fake(['lenco.test/*' => Http::response(['status' => true, 'data' => [
        'reference' => $payment->reference, 'amount' => (string) ($payment->amount_ngwee / 100), 'currency' => 'ZMW', 'status' => 'successful',
    ]])]);

    postWebhook(['event' => 'collection.successful', 'data' => ['reference' => $payment->reference]])
        ->assertOk();

    expect($payment->fresh()->status)->toBe(PaymentStatus::Successful);
});

test('webhooks with a bad signature are rejected', function () {
    $payment = Payment::factory()->create();
    Http::fake();

    postWebhook(['event' => 'collection.successful', 'data' => ['reference' => $payment->reference]], 'forged')
        ->assertUnauthorized();

    Http::assertNothingSent();
    expect($payment->fresh()->status)->toBe(PaymentStatus::Pending);
});

test('unknown references and other events are acknowledged and ignored', function (string $event) {
    Http::fake();

    postWebhook(['event' => $event, 'data' => ['reference' => 'VAR-UNKNOWN']])->assertOk();

    Http::assertNothingSent();
})->with(['collection.successful', 'transfer.successful']);

test('an explicit webhook secret overrides the derived one', function () {
    config(['services.lenco.webhook_secret' => 'explicit']);
    $body = json_encode(['event' => 'transfer.successful']);

    postWebhook(['event' => 'transfer.successful'])->assertUnauthorized();
    postWebhook(['event' => 'transfer.successful'], hash_hmac('sha512', $body, 'explicit'))->assertOk();
});
