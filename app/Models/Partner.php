<?php

namespace App\Models;

use App\Enums\PartnerStatus;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id', 'full_name', 'nrc_number', 'phone', 'payout_method', 'payout_details',
    'status', 'agreement_accepted_at', 'approved_at', 'terminated_at',
])]
class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Lead, $this>
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * @return HasMany<Commission, $this>
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * @return HasMany<Payout, $this>
     */
    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    /**
     * Determine if the partner may register leads.
     */
    public function isActive(): bool
    {
        return $this->status === PartnerStatus::Active;
    }

    /**
     * Get the unpaid commission due at the next payout: everything earned before this month.
     */
    public function amountDueNgwee(): int
    {
        return (int) $this->commissions()->dueForPayout()->sum('amount_ngwee');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PartnerStatus::class,
            'agreement_accepted_at' => 'datetime',
            'approved_at' => 'datetime',
            'terminated_at' => 'datetime',
        ];
    }
}
