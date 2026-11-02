<?php

use App\Enums\PaymentStatus;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = $this->user->currentTeam;
    $this->subscription = Subscription::factory()->create(['team_id' => $this->team->id]);
});

test('open invoices print as an invoice', function () {
    $invoice = Invoice::factory()->create(['subscription_id' => $this->subscription->id, 'amount_ngwee' => 19900]);

    $this->actingAs($this->user)
        ->get(route('invoices.print', ['current_team' => $this->team->slug, 'invoice' => $invoice]))
        ->assertOk()
        ->assertSee('Invoice')
        ->assertSee($invoice->number)
        ->assertSee('Total due')
        ->assertSee('ZMW 199.00')
        ->assertSee($this->team->name);
});

test('paid invoices print as a receipt with the payment reference', function () {
    $invoice = Invoice::factory()->paid()->create(['subscription_id' => $this->subscription->id]);
    Payment::factory()->create(['invoice_id' => $invoice->id, 'status' => PaymentStatus::Successful, 'channel' => 'mobile-money', 'lenco_reference' => 'LEN-777', 'paid_at' => now()]);

    $this->actingAs($this->user)
        ->get(route('invoices.print', ['current_team' => $this->team->slug, 'invoice' => $invoice]))
        ->assertSee('Receipt')
        ->assertSee('Total paid')
        ->assertSee('Mobile Money')
        ->assertSee('LEN-777');
});

test('other teams invoices cannot be printed', function () {
    $this->actingAs($this->user)
        ->get(route('invoices.print', ['current_team' => $this->team->slug, 'invoice' => Invoice::factory()->create()]))
        ->assertNotFound();
});
