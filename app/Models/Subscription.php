<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\SubscriptionStatus;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'team_id', 'plan_id', 'scheduled_plan_id', 'domain', 'hestia_username', 'status', 'current_period_starts_at',
    'current_period_ends_at', 'provisioned_at', 'ssl_issued_at', 'suspended_at', 'cancelled_at',
])]
class Subscription extends Model
{
    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<Plan, $this>
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the plan a downgrade will switch to at the next renewal.
     *
     * @return BelongsTo<Plan, $this>
     */
    public function scheduledPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'scheduled_plan_id');
    }

    /**
     * Get the plan the next renewal invoice should charge for.
     */
    public function renewalPlan(): Plan
    {
        return $this->scheduledPlan ?? $this->plan;
    }

    /**
     * Point an already issued, unpaid renewal invoice at the given plan.
     */
    public function repriceOpenRenewal(Plan $plan): void
    {
        $this->invoices()
            ->where('status', InvoiceStatus::Open)
            ->where('type', InvoiceType::Subscription)
            ->get()
            ->each(fn (Invoice $invoice) => $invoice->update([
                'plan_id' => $plan->id,
                'amount_ngwee' => $plan->price_ngwee,
                'description' => "{$plan->name} hosting for {$this->domain} — 1 month from {$invoice->due_at->toDateString()}",
            ]));
    }

    /**
     * @return HasMany<Invoice, $this>
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the team owner, who receives billing and hosting emails.
     */
    public function billingContact(): ?User
    {
        /** @var User|null */
        return $this->team->owner();
    }

    /**
     * Determine if the customer has asked to stop at the end of the current period.
     */
    public function isCancelling(): bool
    {
        return $this->cancelled_at !== null && $this->status !== SubscriptionStatus::Cancelled;
    }

    /**
     * Determine if the Hestia account should be suspended for this subscription's status.
     */
    public function shouldBeSuspended(): bool
    {
        return in_array($this->status, [SubscriptionStatus::Suspended, SubscriptionStatus::Cancelled], true);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'current_period_starts_at' => 'datetime',
            'current_period_ends_at' => 'datetime',
            'provisioned_at' => 'datetime',
            'ssl_issued_at' => 'datetime',
            'suspended_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }
}
