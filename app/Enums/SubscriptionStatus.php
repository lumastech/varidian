<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case PastDue = 'past_due';
    case Suspended = 'suspended';
    case Cancelled = 'cancelled';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting payment',
            self::Active => 'Active',
            self::PastDue => 'Payment overdue',
            self::Suspended => 'Suspended',
            self::Cancelled => 'Cancelled',
        };
    }
}
