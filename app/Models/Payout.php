<?php

namespace App\Models;

use App\Support\Money;
use Database\Factories\PayoutFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['partner_id', 'amount_ngwee', 'method', 'reference', 'recorded_by', 'paid_at'])]
class Payout extends Model
{
    /** @use HasFactory<PayoutFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return HasMany<Commission, $this>
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /**
     * Get the payout as listed for partners and staff.
     *
     * @return array{id: int, amount: string, method: string, reference: ?string, paidAt: string}
     */
    public function toSummaryArray(): array
    {
        return [
            'id' => $this->id,
            'amount' => Money::format($this->amount_ngwee),
            'method' => $this->method,
            'reference' => $this->reference,
            'paidAt' => $this->paid_at->toDateString(),
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
            'amount_ngwee' => 'integer',
            'paid_at' => 'datetime',
        ];
    }
}
