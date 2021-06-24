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

    'google' => [
        'client_id' => '533511737234-bge2cp87iubvjeaokakf81e13cp11a5f.apps.googleusercontent.com',
        'client_secret' => 'UQy-2Le2b4ciK7vOuqxlWg1B',
        'redirect' => 'http://127.0.0.1:8000/auth/google/callback',
    ],

    'facebook' => [
        'client_id' => '840159919935463',
        'client_secret' => '04ef71460836ee250bc8638b5055b467',
        'redirect' => 'http://localhost/auth/facebook/callback',
    ],
    
    'github' => [
        'client_id' => 'abc2a2245a5f019e9332',
        'client_secret' => '8aff224d5311db4c28446235c8f35e62f76a9a7a',
        'redirect' => 'http://localhost/auth/github/callback',
    ],
];
