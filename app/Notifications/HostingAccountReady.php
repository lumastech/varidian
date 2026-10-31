<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent synchronously from the provisioning job so the password never sits in the queue.
 */
class HostingAccountReady extends Notification
{
    public function __construct(public Subscription $subscription, public string $password) {}

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
        $panelUrl = config('services.hestia.panel_url');

        return (new MailMessage)
            ->subject("Your hosting for {$this->subscription->domain} is ready")
            ->greeting('Your hosting is ready')
            ->line("We've set up {$this->subscription->domain} on the {$this->subscription->plan->name} plan.")
            ->line('Log in to your control panel with:')
            ->line("Username: **{$this->subscription->hestia_username}**")
            ->line("Password: **{$this->password}**")
            ->action('Open control panel', $panelUrl)
            ->line('Please change this password after your first login.')
            ->line("Point your domain's A record to our server to go live. We'll issue the free SSL certificate once it resolves; ask us on WhatsApp if you need help.");
    }
}
