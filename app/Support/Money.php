<?php

namespace App\Support;

class Money
{
    /**
     * Format an amount in ngwee for display, e.g. 19900 => "199.00".
     */
    public static function format(int $ngwee): string
    {
        return number_format($ngwee / 100, 2);
    }

    /**
     * Convert a kwacha amount entered in a form to ngwee.
     */
    public static function toNgwee(float|int|string $kwacha): int
    {
        return (int) round((float) $kwacha * 100);
    }
}
