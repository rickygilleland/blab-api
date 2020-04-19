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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'client_id' => env('SLACK_KEY'),
        'client_secret' => env('SLACK_SECRET'),
        'redirect' => env('SLACK_REDIRECT_URI')
    ],

    'twilio' => [
	    'sid' => env('TWILIO_ACCOUNT_SID'),
	    'token' => env('TWILIO_ACCOUNT_TOKEN'),
	    'key' => env('TWILIO_API_KEY'),
        'secret' => env('TWILIO_API_SECRET'),
    ],

    'streaming_backend' => [
        'url' => env('STREAMING_BACKEND_API_URL'),
        'secret' => env('STREAMING_BACKEND_API_SECRET')
    ]
];
