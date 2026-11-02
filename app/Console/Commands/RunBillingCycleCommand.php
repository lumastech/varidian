<?php

namespace App\Console\Commands;

use App\Actions\Billing\RunBillingCycle;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('billing:run')]
#[Description('Issue renewal invoices, mark overdue hosting, suspend unpaid accounts and retry SSL')]
class RunBillingCycleCommand extends Command
{
    public function handle(RunBillingCycle $billingCycle): int
    {
        $results = $billingCycle->handle();

        $this->table(['Step', 'Subscriptions'], collect($results)->map(fn (int $count, string $step) => [$step, $count])->values());

        return self::SUCCESS;
    }
}
