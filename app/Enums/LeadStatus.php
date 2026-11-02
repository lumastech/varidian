<?php

namespace App\Enums;

enum LeadStatus: string
{
    /**
     * Registered by the partner, waiting for Varidian to confirm it.
     */
    case Pending = 'pending';

    /**
     * Confirmed: the partner owns it until it expires.
     */
    case Confirmed = 'confirmed';

    /**
     * A meeting or proposal has happened, so ownership no longer expires.
     */
    case Engaged = 'engaged';

    case Won = 'won';
    case Lost = 'lost';

    /**
     * Already registered by someone else, or an existing Varidian client.
     */
    case Rejected = 'rejected';

    /**
     * Get the display label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting confirmation',
            self::Confirmed => 'Registered',
            self::Engaged => 'In progress',
            self::Won => 'Signed',
            self::Lost => 'Lost',
            self::Rejected => 'Not accepted',
        };
    }
}
