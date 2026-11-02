<?php

namespace App\Notifications;

use App\Enums\LeadStatus;
use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadReviewed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lead $lead) {}

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
        $message = (new MailMessage)->subject("Lead {$this->lead->status->label()}: {$this->lead->organisation}");

        $message = $this->lead->status === LeadStatus::Confirmed
            ? $message->greeting('Your lead is registered')
                ->line("{$this->lead->organisation} is yours until {$this->lead->expires_at->toDateString()}. Book a meeting or proposal with us before then to keep it.")
            : $message->greeting('We could not register this lead')
                ->line("{$this->lead->organisation} is already registered by another partner or is an existing Varidian client.");

        if ($this->lead->review_note) {
            $message->line("Note from Varidian: {$this->lead->review_note}");
        }

        return $message->action('Open partner portal', route('partner.dashboard'));
    }
}
