<?php

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Jobs\ProvisionHostingAccount;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    config([
        'services.lenco.public_key' => 'pub-test',
        'services.lenco.secret_key' => 'sec-test',
        'services.lenco.api_url' => 'https://lenco.test/access/v2',
    ]);

    $this->user = User::factory()->create();
    $this->team = $this->user->currentTeam;
    $this->subscription = Subscription::factory()->create(['team_id' => $this->team->id]);
    $this->invoice = Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'amount_ngwee' => 19900]);
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function lencoCollection(string $reference, array $overrides = []): array
{
    return array_merge([
        'id' => 'col-1',
        'reference' => $reference,
        'lencoReference' => 'LEN-123',
        'amount' => '199.00',
        'currency' => 'ZMW',
        'type' => 'mobile-money',
        'status' => 'successful',
        'reasonForFailure' => null,
    ], $overrides);
}

function fakeLenco(array $collection): void
{
    Http::fake(['lenco.test/*' => Http::response(['status' => true, 'message' => '', 'data' => $collection])]);
}

function verifyRoute(Payment $payment): string
{
    return route('invoices.payments.verify', ['current_team' => $payment->invoice->team->slug, 'invoice' => $payment->invoice, 'payment' => $payment]);
}

test('the invoice page renders with the widget for open invoices', function () {
    $this->actingAs($this->user)
        ->get(route('invoices.show', ['current_team' => $this->team->slug, 'invoice' => $this->invoice]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('billing/Invoice')
            ->where('invoice.amount', '199.00')
            ->where('canPay', true)
            ->where('widgetUrl', 'https://pay.sandbox.lenco.co/js/v1/inline.js'));
});

test('starting a payment returns the widget parameters', function () {
    $response = $this->actingAs($this->user)
        ->postJson(route('invoices.payments.store', ['current_team' => $this->team->slug, 'invoice' => $this->invoice]))
        ->assertCreated()
        ->assertJson(['key' => 'pub-test', 'amount' => 199, 'currency' => 'ZMW', 'email' => $this->user->email]);

    $payment = Payment::sole();

    expect($response->json('reference'))->toBe($payment->reference)
        ->and($payment->reference)->toMatch('/^VAR-[0-9A-Z]{26}$/')
        ->and($payment->status)->toBe(PaymentStatus::Pending)
        ->and($response->json())->not->toHaveKey('secret');
});

test('paid invoices cannot start a new payment', function () {
    $this->invoice->update(['status' => InvoiceStatus::Paid]);

    $this->actingAs($this->user)
        ->postJson(route('invoices.payments.store', ['current_team' => $this->team->slug, 'invoice' => $this->invoice]))
        ->assertConflict();
});

test('a verified payment pays the invoice, activates the subscription and queues provisioning', function () {
    Queue::fake();
    $payment = Payment::factory()->create(['invoice_id' => $this->invoice->id]);
    fakeLenco(lencoCollection($payment->reference));

    $this->actingAs($this->user)
        ->post(verifyRoute($payment))
        ->assertRedirect()
        ->assertSessionHas('status', 'payment-successful');

    Http::assertSent(fn ($request) => $request->url() === "https://lenco.test/access/v2/collections/status/{$payment->reference}"
        && $request->hasHeader('Authorization', 'Bearer sec-test'));

    expect($payment->fresh())
        ->status->toBe(PaymentStatus::Successful)
        ->lenco_reference->toBe('LEN-123')
        ->channel->toBe('mobile-money')
        ->and($this->invoice->fresh()->status)->toBe(InvoiceStatus::Paid)
        ->and($this->subscription->fresh())
        ->status->toBe(SubscriptionStatus::Active)
        ->current_period_ends_at->toDateString()->toBe(now()->addMonthNoOverflow()->toDateString());

    Queue::assertPushed(ProvisionHostingAccount::class, fn ($job) => $job->subscription->is($this->subscription));
});

test('confirming twice settles the payment only once', function () {
    Queue::fake();
    $payment = Payment::factory()->create(['invoice_id' => $this->invoice->id]);
    fakeLenco(lencoCollection($payment->reference));

    $this->actingAs($this->user)->post(verifyRoute($payment));
    $this->actingAs($this->user)->post(verifyRoute($payment));

    Http::assertSentCount(1);
    Queue::assertPushed(ProvisionHostingAccount::class, 1);
});

test('a payment for the wrong amount or currency is rejected', function (array $overrides) {
    Queue::fake();
    $payment = Payment::factory()->create(['invoice_id' => $this->invoice->id]);
    fakeLenco(lencoCollection($payment->reference, $overrides));

    $this->actingAs($this->user)
        ->post(verifyRoute($payment))
        ->assertSessionHas('status', 'payment-failed');

    expect($payment->fresh()->status)->toBe(PaymentStatus::Failed)
        ->and($this->invoice->fresh()->status)->toBe(InvoiceStatus::Open);

    Queue::assertNothingPushed();
})->with([
    'underpaid' => [['amount' => '1.00']],
    'other currency' => [['currency' => 'USD']],
]);

test('failed and pending collections are recorded without paying the invoice', function (string $lencoStatus, PaymentStatus $expected, string $flash) {
    $payment = Payment::factory()->create(['invoice_id' => $this->invoice->id]);
    fakeLenco(lencoCollection($payment->reference, ['status' => $lencoStatus, 'reasonForFailure' => 'Insufficient balance']));

    $this->actingAs($this->user)
        ->post(verifyRoute($payment))
        ->assertSessionHas('status', $flash);

    expect($payment->fresh()->status)->toBe($expected)
        ->and($this->invoice->fresh()->status)->toBe(InvoiceStatus::Open);
})->with([
    ['failed', PaymentStatus::Failed, 'payment-failed'],
    ['pending', PaymentStatus::Pending, 'payment-pending'],
]);

test('renewal payments extend the current period', function () {
    $endsAt = now()->addDays(5)->startOfSecond();
    $this->subscription->update(['status' => SubscriptionStatus::Active, 'current_period_ends_at' => $endsAt, 'provisioned_at' => now()]);
    Queue::fake();
    $payment = Payment::factory()->create(['invoice_id' => $this->invoice->id]);
    fakeLenco(lencoCollection($payment->reference));

    $this->actingAs($this->user)->post(verifyRoute($payment));

    expect($this->subscription->fresh()->current_period_ends_at->equalTo($endsAt->copy()->addMonthNoOverflow()))->toBeTrue();
    Queue::assertNothingPushed();
});

test('payments of other invoices cannot be verified through this one', function () {
    $payment = Payment::factory()->create();

    $this->actingAs($this->user)
        ->post(route('invoices.payments.verify', ['current_team' => $this->team->slug, 'invoice' => $this->invoice, 'payment' => $payment]))
        ->assertNotFound();
});
