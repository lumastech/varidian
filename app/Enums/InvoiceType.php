<?php

namespace App\Enums;

enum InvoiceType: string
{
    /**
     * Pays for a billing period: the first month or a renewal.
     */
    case Subscription = 'subscription';

    /**
     * Pays the prorated difference for an upgrade mid-period.
     */
    case PlanChange = 'plan_change';
}
