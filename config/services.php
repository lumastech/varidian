<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'hestia' => [
        'url' => env('HESTIA_URL'),
        'access_key' => env('HESTIA_ACCESS_KEY'),
        'secret_key' => env('HESTIA_SECRET_KEY'),
        'resolve_ip' => env('HESTIA_RESOLVE_IP'),
        'verify_ssl' => env('HESTIA_VERIFY_SSL', true),
        'timeout' => env('HESTIA_TIMEOUT', 30),
        'panel_url' => env('HESTIA_PANEL_URL'),
    ],

    'lenco' => [
        'environment' => env('LENCO_ENVIRONMENT', 'sandbox'),
        'public_key' => env('LENCO_PUBLIC_KEY'),
        'secret_key' => env('LENCO_SECRET_KEY'),
        'account_id' => env('LENCO_ACCOUNT_ID'),
        'webhook_secret' => env('LENCO_WEBHOOK_SECRET'),
        'api_url' => env('LENCO_API_URL', 'https://api.lenco.co/access/v2'),
        'widget_url' => env('LENCO_WIDGET_URL'),
        'fee_bearer' => env('LENCO_FEE_BEARER', 'merchant'),
        'timeout' => env('LENCO_HTTP_TIMEOUT', 30),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
