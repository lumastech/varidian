<?php

namespace App\Http\Controllers\Billing;

use App\Actions\Billing\CancelSubscription;
use App\Enums\SubscriptionStatus;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;

class SubscriptionController extends Controller
{
    public function cancel(Team $current_team, Subscription $subscription, CancelSubscription $cancelSubscription): RedirectResponse
    {
        abort_if($subscription->cancelled_at || $subscription->status === SubscriptionStatus::Cancelled, 409, 'This hosting is already cancelled.');

        $cancelSubscription->handle($subscription);

        return to_route('dashboard', ['current_team' => $current_team->slug]);
    }
}
