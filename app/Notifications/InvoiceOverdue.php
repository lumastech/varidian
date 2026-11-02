<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceOverdue extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Invoice $invoice) {}

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
        $suspendOn = $this->invoice->due_at->addDays(config('billing.grace_days'))->toDateString();

        return (new MailMessage)
            ->subject("Overdue: invoice {$this->invoice->number}")
            ->greeting('Your hosting payment is overdue')
            ->line("We haven't received payment for invoice {$this->invoice->number} (ZMW {$this->invoice->formattedAmount()}).")
            ->line("Your website stays online until {$suspendOn}. After that it will be suspended until the invoice is paid.")
            ->action('Pay invoice', route('invoices.show', ['current_team' => $this->invoice->team->slug, 'invoice' => $this->invoice]));
    }
}
