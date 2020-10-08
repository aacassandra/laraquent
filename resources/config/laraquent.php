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

    'model' => [
        /**
         * The default directory of models
         */
        'directory' => 'App\Models',
        /**
         * Default of User Model
         */
        'user' => 'App\Models\User',
        /**
         * If using [spatie/laravel-permission]
         * you must set default model off this
         */
        'role' => 'Spatie\Permission\Models\Role',
        'permission' => 'Spatie\Permission\Models\Permission'
    ],

    /**
     * If you want get response without failure checking
     */
    'failure' => [
        'checking' => false
    ]
];
