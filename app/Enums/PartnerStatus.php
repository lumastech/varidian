<?php

namespace App\Enums;

enum PartnerStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Terminated = 'terminated';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting approval',
            self::Active => 'Active',
            self::Terminated => 'Ended',
        };
    }
}
