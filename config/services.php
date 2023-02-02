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

    'facebook' => [

        'client_id'     => '2983261785254221',
        'client_secret' => '81fde4d1a1ad3c34637c0a184c5d5d79',
        'redirect'      => 'https://knosh.in/auth/facebook/callback',
    ],

    'google' => [

        'client_id'     => '568633280145-lrv6nscap8k01nt08t26gidqkfdj7oij.apps.googleusercontent.com',
        'client_secret' => 'HZg7-vW8AjeudlDmKyvZby8a',
        'redirect'      => 'https://knosh.in/auth/google/callback',
    ],

    'gitlab' => [

        'client_id'     => env('GITLAB_CLIENT_ID'),
        'client_secret' => env('GITLAB_CLIENT_SECRET'),
        'redirect'      => env('GITLAB_CALLBACK_URL'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),         // Your LinkedIn Client ID
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'), // Your LinkedIn Client Secret
        'redirect' => env('LINKEDIN_CALLBACK_URL'),       // Your LinkedIn Callback URL
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),  // Your Twitter Client ID
        'client_secret' => env('TWITTER_CLIENT_SECRET'), // Your Twitter Client Secret
        'redirect' => env('TWITTER_CALLBACK_URL'),
    ],
    


];
