<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['number', 'team_id', 'subscription_id', 'description', 'amount_ngwee', 'status', 'due_at', 'paid_at'])]
class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory;

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Invoice $invoice) {
            if (empty($invoice->number)) {
                $invoice->number = 'INV-'.str_pad((string) $invoice->id, 6, '0', STR_PAD_LEFT);
                $invoice->saveQuietly();
            }
        });
    }

    /**
     * @return BelongsTo<Team, $this>
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * @return BelongsTo<Subscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return HasMany<Payment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the amount in kwacha, as Lenco expects it.
     */
    public function amountInKwacha(): float
    {
        return $this->amount_ngwee / 100;
    }

    /**
     * Get the invoice as passed to customer pages.
     *
     * @return array{id: int, number: string, description: string, amount: string, status: string, statusLabel: string, dueAt: string, paidAt: ?string}
     */
    public function toCustomerArray(): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'description' => $this->description,
            'amount' => number_format($this->amountInKwacha(), 2),
            'status' => $this->status->value,
            'statusLabel' => $this->status->label(),
            'dueAt' => $this->due_at->toDateString(),
            'paidAt' => $this->paid_at?->toDateString(),
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
            'status' => InvoiceStatus::class,
            'amount_ngwee' => 'integer',
            'due_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }
}
