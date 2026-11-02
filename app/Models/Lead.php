<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Database\Factories\LeadFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'partner_id', 'organisation', 'contact_name', 'contact_phone', 'contact_email', 'interest', 'notes',
    'status', 'review_note', 'team_id', 'confirmed_at', 'expires_at', 'won_at',
])]
class Lead extends Model
{
    /** @use HasFactory<LeadFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * Get the client team the lead became once signed.
     *
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Scope the query to leads a partner currently owns or is waiting to hear about.
     *
     * @param  Builder<Lead>  $query
     */
    public function scopeHeld(Builder $query): void
    {
        $query->where(fn (Builder $query) => $query
            ->whereIn('status', [LeadStatus::Pending, LeadStatus::Engaged, LeadStatus::Won])
            ->orWhere(fn (Builder $query) => $query
                ->where('status', LeadStatus::Confirmed)
                ->where('expires_at', '>', now())));
    }

    /**
     * Determine if a confirmed lead went 90 days without a meeting or proposal and is open again.
     */
    public function isExpired(): bool
    {
        return $this->status === LeadStatus::Confirmed && $this->expires_at?->isPast();
    }

    /**
     * Get the lead as shown to partners and staff.
     *
     * @return array{id: int, organisation: string, contactName: string, contactPhone: string, contactEmail: ?string, interest: string, notes: ?string, status: string, statusLabel: string, reviewNote: ?string, isExpired: bool, registeredAt: string, expiresAt: ?string, wonAt: ?string}
     */
    public function toPortalArray(): array
    {
        return [
            'id' => $this->id,
            'organisation' => $this->organisation,
            'contactName' => $this->contact_name,
            'contactPhone' => $this->contact_phone,
            'contactEmail' => $this->contact_email,
            'interest' => $this->interest,
            'notes' => $this->notes,
            'status' => $this->status->value,
            'statusLabel' => $this->isExpired() ? 'Expired — open again' : $this->status->label(),
            'reviewNote' => $this->review_note,
            'isExpired' => $this->isExpired(),
            'registeredAt' => $this->created_at->toDateString(),
            'expiresAt' => $this->expires_at?->toDateString(),
            'wonAt' => $this->won_at?->toDateString(),
        ];
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
            'confirmed_at' => 'datetime',
            'expires_at' => 'datetime',
            'won_at' => 'datetime',
        ];
    }
}
