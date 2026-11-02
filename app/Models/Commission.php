<?php

namespace App\Models;

use App\Enums\CommissionType;
use App\Support\Money;
use Database\Factories\CommissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'partner_id', 'lead_id', 'invoice_id', 'payout_id', 'type', 'description',
    'base_ngwee', 'rate_percent', 'amount_ngwee', 'earned_at',
])]
class Commission extends Model
{
    /** @use HasFactory<CommissionFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Partner, $this>
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /**
     * @return BelongsTo<Lead, $this>
     */
    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the client invoice this commission was earned on, for hosting.
     *
     * @return BelongsTo<Invoice, $this>
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * @return BelongsTo<Payout, $this>
     */
    public function payout(): BelongsTo
    {
        return $this->belongsTo(Payout::class);
    }

    /**
     * Scope the query to unpaid commission earned before the current month, which
     * Varidian pays by the 10th.
     *
     * @param  Builder<Commission>  $query
     */
    public function scopeDueForPayout(Builder $query): void
    {
        $query->whereNull('payout_id')->where('earned_at', '<', now()->startOfMonth());
    }

    /**
     * Calculate the commission on an amount the client paid at the type's current rate.
     *
     * @return array{base_ngwee: int, rate_percent: ?int, amount_ngwee: int}
     */
    public static function calculate(CommissionType $type, int $baseNgwee): array
    {
        $rate = $type->ratePercent();

        return [
            'base_ngwee' => $baseNgwee,
            'rate_percent' => $rate,
            'amount_ngwee' => $rate === null ? $baseNgwee : (int) round($baseNgwee * $rate / 100),
        ];
    }

    /**
     * Get the commission as listed on statements.
     *
     * @return array{id: int, type: string, typeLabel: string, client: ?string, description: string, received: ?string, rate: ?int, amount: string, earnedAt: string, isPaid: bool}
     */
    public function toStatementArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type->value,
            'typeLabel' => $this->type->label(),
            'client' => $this->lead?->organisation,
            'description' => $this->description,
            'received' => $this->rate_percent === null ? null : Money::format($this->base_ngwee),
            'rate' => $this->rate_percent,
            'amount' => Money::format($this->amount_ngwee),
            'earnedAt' => $this->earned_at->toDateString(),
            'isPaid' => $this->payout_id !== null,
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
            'type' => CommissionType::class,
            'base_ngwee' => 'integer',
            'rate_percent' => 'integer',
            'amount_ngwee' => 'integer',
            'earned_at' => 'datetime',
        ];
    }
}
