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
    'commercetools' => [
        'project_key' => env('CTP_PROJECT_KEY'),
        'client_id' => env('CTP_CLIENT_ID'),
        'client_secret' => env('CTP_CLIENT_SECRET'),
    ],


];
