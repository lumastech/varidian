<?php

use App\Enums\InvoiceStatus;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Jobs\ProvisionHostingAccount;
use App\Models\ContactInquiry;
use App\Models\Invoice;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
});

test('customers cannot open the admin area', function () {
    $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();
});

test('registration cannot make someone an admin', function () {
    $this->post(route('register.store'), [
        'name' => 'Sneaky',
        'email' => 'sneaky@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'is_admin' => true,
    ]);

    expect(User::where('email', 'sneaky@example.com')->sole()->is_admin)->toBeFalse();
});

test('admins see subscriptions, open invoices and inquiries', function () {
    Subscription::factory()->active()->create(['domain' => 'acme.co.zm']);
    Invoice::factory()->create();
    Invoice::factory()->paid()->create();
    ContactInquiry::factory()->create(['name' => 'Mwila']);

    $this->actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('admin/Dashboard')
            ->has('subscriptions', 3)
            ->has('openInvoices', 1)
            ->where('inquiries.0.name', 'Mwila'));
});

test('admins can record an offline payment', function () {
    Queue::fake();
    $invoice = Invoice::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.invoices.offline-payments.store', $invoice), ['method' => 'bank-transfer', 'note' => 'ZANACO ref 991'])
        ->assertRedirect();

    $payment = $invoice->payments()->sole();

    expect($payment)
        ->status->toBe(PaymentStatus::Successful)
        ->channel->toBe('bank-transfer')
        ->provider_response->toMatchArray(['recorded_by' => $this->admin->id, 'note' => 'ZANACO ref 991'])
        ->and($invoice->fresh()->status)->toBe(InvoiceStatus::Paid)
        ->and($invoice->subscription->fresh()->status)->toBe(SubscriptionStatus::Active);

    Queue::assertPushed(ProvisionHostingAccount::class);
});

test('offline payments need a valid method and an open invoice', function () {
    $invoice = Invoice::factory()->create();

    $this->actingAs($this->admin)
        ->post(route('admin.invoices.offline-payments.store', $invoice), ['method' => 'crypto'])
        ->assertSessionHasErrors('method');

    $invoice->update(['status' => InvoiceStatus::Void]);

    $this->actingAs($this->admin)
        ->post(route('admin.invoices.offline-payments.store', $invoice), ['method' => 'cash'])
        ->assertConflict();
});

test('customers cannot record offline payments', function () {
    $invoice = Invoice::factory()->create();

    $this->actingAs(User::factory()->create())
        ->post(route('admin.invoices.offline-payments.store', $invoice), ['method' => 'cash'])
        ->assertForbidden();

    expect($invoice->fresh()->status)->toBe(InvoiceStatus::Open);
});
