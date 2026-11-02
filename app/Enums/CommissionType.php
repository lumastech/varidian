<?php

namespace App\Enums;

enum CommissionType: string
{
    case Subscription = 'subscription';
    case Licence = 'licence';
    case Project = 'project';
    case Hosting = 'hosting';
    case Referral = 'referral';

    /**
     * A correction entered as a commission amount, e.g. a deduction for a client refund.
     */
    case Adjustment = 'adjustment';

    /**
     * Get the display label for the type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Subscription => 'Software subscription',
            self::Licence => 'Licence or setup fee',
            self::Project => 'Custom software project',
            self::Hosting => 'Hosting and domains',
            self::Referral => 'Referral only',
            self::Adjustment => 'Adjustment',
        };
    }

    /**
     * Get the commission rate as a whole percentage, or null for adjustments.
     */
    public function ratePercent(): ?int
    {
        return config("partners.rates.{$this->value}");
    }
}
