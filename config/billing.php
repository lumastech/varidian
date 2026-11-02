<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hosting Billing Cycle
    |--------------------------------------------------------------------------
    |
    | Renewal invoices are issued this many days before a period ends. Hosting
    | that is still unpaid this many days after the period ends is suspended.
    | Suspended accounts are never deleted automatically; staff do that.
    |
    */

    'renewal_lead_days' => (int) env('BILLING_RENEWAL_LEAD_DAYS', 7),

    'grace_days' => (int) env('BILLING_GRACE_DAYS', 7),

    'ssl_retry_days' => (int) env('BILLING_SSL_RETRY_DAYS', 30),

];
