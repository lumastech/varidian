<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalInvoiceIssued extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject("Invoice {$this->invoice->number}: ZMW {$this->invoice->formattedAmount()} due {$this->invoice->due_at->toDateString()}")
            ->greeting('Your hosting renews soon')
            ->line($this->invoice->description.'.')
            ->line("Please pay ZMW {$this->invoice->formattedAmount()} by {$this->invoice->due_at->toDateString()} to keep your website online.")
            ->action('Pay invoice', route('invoices.show', ['current_team' => $this->invoice->team->slug, 'invoice' => $this->invoice]))
            ->line('You can pay by mobile money or card.');
    }
}
