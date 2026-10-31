<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Successful = 'successful';
    case Failed = 'failed';

    /**
     * Map a Lenco collection status onto ours; anything still in flight stays pending.
     */
    public static function fromLenco(?string $status): self
    {
        return match ($status) {
            'successful' => self::Successful,
            'failed' => self::Failed,
            default => self::Pending,
        };
    }

    /**
     * Determine if the payment can no longer change.
     */
    public function isFinal(): bool
    {
        return $this !== self::Pending;
    }
}
