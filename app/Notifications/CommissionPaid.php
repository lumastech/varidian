<?php

namespace App\Notifications;

use App\Models\Payout;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommissionPaid extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payout $payout) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $amount = Money::format($this->payout->amount_ngwee);

        return (new MailMessage)
            ->subject("Commission paid: ZMW {$amount}")
            ->greeting('Your commission has been paid')
            ->line("We've sent ZMW {$amount} by ".str_replace('-', ' ', $this->payout->method).'.')
            ->action('View statement', route('partner.payouts.show', $this->payout))
            ->line('The statement lists each client, the amount we received and the commission due.');
    }
}
