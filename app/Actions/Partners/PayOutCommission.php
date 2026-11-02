<?php

namespace App\Actions\Partners;

use App\Models\Partner;
use App\Models\Payout;
use App\Models\User;
use App\Notifications\CommissionPaid;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayOutCommission
{
    /**
     * Record that the partner has been paid everything due and send them their statement.
     */
    public function handle(Partner $partner, string $method, ?string $reference, User $recordedBy): Payout
    {
        $payout = DB::transaction(function () use ($partner, $method, $reference, $recordedBy) {
            $commissions = $partner->commissions()->dueForPayout()->lockForUpdate()->get();
            $total = (int) $commissions->sum('amount_ngwee');

            // Refund deductions larger than what was earned carry over to the next month.
            if ($total <= 0) {
                throw ValidationException::withMessages(['payout' => 'There is no commission due to this partner.']);
            }

            $payout = $partner->payouts()->create([
                'amount_ngwee' => $total,
                'method' => $method,
                'reference' => $reference,
                'recorded_by' => $recordedBy->id,
                'paid_at' => now(),
            ]);

            $partner->commissions()->whereKey($commissions->modelKeys())->update(['payout_id' => $payout->id]);

            return $payout;
        });

        $partner->user->notify(new CommissionPaid($payout));

        return $payout;
    }
}
