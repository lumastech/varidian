<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sales Partner Programme
    |--------------------------------------------------------------------------
    |
    | Rates are whole percentages of what the client pays, excluding VAT and
    | third-party costs. Each commission stores the rate it was earned at, so
    | changing a rate here (with 30 days' notice) never rewrites history.
    |
    */

    'rates' => [
        'subscription' => 20,
        'licence' => 15,
        'project' => 10,
        'hosting' => 15,
        'referral' => 5,
    ],

    'lead_ownership_days' => (int) env('PARTNER_LEAD_OWNERSHIP_DAYS', 90),

    'recurring_commission_months' => (int) env('PARTNER_RECURRING_COMMISSION_MONTHS', 12),

];
