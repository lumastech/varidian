<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Open = 'open';
    case Paid = 'paid';
    case Void = 'void';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return ucfirst($this->value);
    }
}
