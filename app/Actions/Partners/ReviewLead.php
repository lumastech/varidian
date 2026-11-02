<?php

namespace App\Actions\Partners;

use App\Enums\InvoiceStatus;
use App\Enums\LeadStatus;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Team;
use App\Notifications\LeadReviewed;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewLead
{
    /**
     * The statuses each review decision can be made from.
     *
     * @var array<string, list<LeadStatus>>
     */
    private const array AllowedFrom = [
        'confirm' => [LeadStatus::Pending, LeadStatus::Rejected],
        'reject' => [LeadStatus::Pending],
        'engage' => [LeadStatus::Confirmed],
        'extend' => [LeadStatus::Confirmed],
        'won' => [LeadStatus::Confirmed, LeadStatus::Engaged],
        'lost' => [LeadStatus::Confirmed, LeadStatus::Engaged],
    ];

    public function __construct(private RecordHostingCommission $recordHostingCommission) {}

    /**
     * Apply a staff decision to a partner's lead.
     */
    public function handle(Lead $lead, string $decision, ?string $note = null, ?Team $team = null): void
    {
        if (! in_array($lead->status, self::AllowedFrom[$decision], true)) {
            throw ValidationException::withMessages([
                'decision' => "A lead that is \"{$lead->status->label()}\" can't be marked that way.",
            ]);
        }

        $ownershipDays = config('partners.lead_ownership_days');

        DB::transaction(function () use ($lead, $decision, $note, $team, $ownershipDays) {
            match ($decision) {
                'confirm' => $lead->update([
                    'status' => LeadStatus::Confirmed,
                    'review_note' => $note,
                    'confirmed_at' => now(),
                    'expires_at' => now()->addDays($ownershipDays),
                ]),
                'reject' => $lead->update(['status' => LeadStatus::Rejected, 'review_note' => $note]),
                'engage' => $lead->update(['status' => LeadStatus::Engaged]),
                'extend' => $lead->update([
                    'expires_at' => ($lead->expires_at->isPast() ? now() : $lead->expires_at)->addDays($ownershipDays),
                ]),
                'won' => $this->markWon($lead, $team),
                'lost' => $lead->update(['status' => LeadStatus::Lost, 'review_note' => $note]),
            };
        });

        if (in_array($decision, ['confirm', 'reject'], true)) {
            $lead->partner->user->notify(new LeadReviewed($lead));
        }
    }

    /**
     * Link the lead to the client's team and credit any hosting the client has already paid for.
     */
    private function markWon(Lead $lead, Team $team): void
    {
        $lead->update(['status' => LeadStatus::Won, 'team_id' => $team->id, 'won_at' => now()]);

        $team->invoices()
            ->where('status', InvoiceStatus::Paid)
            ->where('paid_at', '>=', $lead->created_at)
            ->oldest('paid_at')
            ->get()
            ->each(fn (Invoice $invoice) => $this->recordHostingCommission->handle($invoice));
    }
}
