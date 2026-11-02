<?php

use App\Enums\LeadStatus;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Partner;
use App\Models\Team;
use App\Models\User;
use App\Notifications\LeadReviewed;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->partner = Partner::factory()->create();
});

test('active partners register leads for confirmation', function () {
    $this->actingAs($this->partner->user)
        ->post(route('partner.leads.store'), [
            'organisation' => 'Kabulonga Hostels',
            'contact_name' => 'Mutale Banda',
            'contact_phone' => '+260977000000',
            'interest' => 'HostelHub',
        ])
        ->assertRedirect();

    expect($this->partner->leads()->sole())
        ->organisation->toBe('Kabulonga Hostels')
        ->status->toBe(LeadStatus::Pending);
});

test('staff see when a lead is already registered or an existing client', function () {
    Lead::factory()->confirmed()->create(['organisation' => 'Kabulonga Hostels']);
    Lead::factory()->confirmed()->create(['organisation' => 'Lapsed Ltd', 'expires_at' => now()->subDay()]);
    Invoice::factory()->create(['team_id' => Team::factory()->create(['name' => 'Acme Ltd'])]);

    Lead::factory()->for($this->partner)->create(['organisation' => 'kabulonga hostels']);
    Lead::factory()->for($this->partner)->create(['organisation' => 'Acme Ltd']);
    Lead::factory()->for($this->partner)->create(['organisation' => 'Lapsed Ltd']);

    $this->actingAs($this->admin)
        ->get(route('admin.partners.index'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('admin/partners/Index')
            ->has('leadsToReview', 3)
            ->has('leadsToReview.0.conflicts', 1)
            ->where('leadsToReview.1.conflicts', ['Existing client account: Acme Ltd'])
            ->where('leadsToReview.2.conflicts', []));
});

test('confirming a lead gives the partner 90 days and tells them', function () {
    Notification::fake();
    $lead = Lead::factory()->for($this->partner)->create();

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'confirm'])->assertRedirect();

    expect($lead->fresh())
        ->status->toBe(LeadStatus::Confirmed)
        ->expires_at->toDateString()->toBe(now()->addDays(90)->toDateString());

    Notification::assertSentTo($this->partner->user, LeadReviewed::class);
});

test('rejecting a lead needs a reason', function () {
    Notification::fake();
    $lead = Lead::factory()->for($this->partner)->create();

    $this->actingAs($this->admin)
        ->put(route('admin.leads.update', $lead), ['decision' => 'reject'])
        ->assertSessionHasErrors('note');

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'reject', 'note' => 'Existing client']);

    expect($lead->fresh())->status->toBe(LeadStatus::Rejected)->review_note->toBe('Existing client');
});

test('a confirmed lead lapses after 90 days without a meeting or proposal', function () {
    $lead = Lead::factory()->for($this->partner)->confirmed()->create();

    $this->travel(91)->days();

    expect($lead->fresh()->isExpired())->toBeTrue()
        ->and(Lead::query()->held()->count())->toBe(0);

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'extend']);

    expect($lead->fresh()->isExpired())->toBeFalse()
        ->and($lead->fresh()->expires_at->toDateString())->toBe(now()->addDays(90)->toDateString());
});

test('engaged leads no longer expire', function () {
    $lead = Lead::factory()->for($this->partner)->confirmed()->create();

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'engage']);
    $this->travel(200)->days();

    expect($lead->fresh())->status->toBe(LeadStatus::Engaged)->isExpired()->toBeFalse();
});

test('decisions must make sense for the lead status', function () {
    $lead = Lead::factory()->for($this->partner)->create();

    $this->actingAs($this->admin)
        ->put(route('admin.leads.update', $lead), ['decision' => 'won', 'team_id' => Team::factory()->create()->id])
        ->assertSessionHasErrors('decision');

    expect($lead->fresh()->status)->toBe(LeadStatus::Pending);
});

test('winning a lead requires the client account', function () {
    $lead = Lead::factory()->for($this->partner)->confirmed()->create();
    $team = Team::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.leads.update', $lead), ['decision' => 'won'])
        ->assertSessionHasErrors('team_id');

    $this->actingAs($this->admin)->put(route('admin.leads.update', $lead), ['decision' => 'won', 'team_id' => $team->id]);

    expect($lead->fresh())->status->toBe(LeadStatus::Won)->team_id->toBe($team->id);
});

test('partners see their own leads', function () {
    Lead::factory()->for($this->partner)->confirmed()->create(['organisation' => 'Mine']);
    Lead::factory()->create(['organisation' => 'Theirs']);

    $this->actingAs($this->partner->user)
        ->get(route('partner.dashboard'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('partners/Dashboard')
            ->has('leads', 1)
            ->where('leads.0.organisation', 'Mine')
            ->where('leads.0.statusLabel', 'Registered'));
});
