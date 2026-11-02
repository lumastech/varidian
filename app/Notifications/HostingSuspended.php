<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HostingSuspended extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Subscription $subscription) {}

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
        return (new MailMessage)
            ->subject("Hosting suspended: {$this->subscription->domain}")
            ->greeting('Your hosting has been suspended')
            ->line("{$this->subscription->domain} is offline because its invoice is unpaid. Your files and databases are safe.")
            ->line('Pay the outstanding invoice and we will bring it back online automatically.')
            ->action('View your account', route('dashboard', ['current_team' => $this->subscription->team->slug]));
    }
}
