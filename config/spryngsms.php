<?php

return [
    /*
    |----------------------------------------------------------------------
    | Authentication token
    |----------------------------------------------------------------------
    |
    | Here we need to define the token for api authentication
    |
    */
    'token' => env('SPRYNG_SMS_API_TOKEN'),

    /*
    |----------------------------------------------------------------------
    | From / Originator
    |----------------------------------------------------------------------
    |
    | The sender of the message. Can be alphanumeric string (max. 11 characters)
    | or phonenumber (max. 14 digits in E.164 format like 31612345678)
    |
    */
    'originator' => env('SPRYNG_SMS_FROM_NAME', 'Oscar'),

    /*
    |----------------------------------------------------------------------
    | Route
    |----------------------------------------------------------------------
    |
    | Your given route to send the message on. Can be a valid route
    | ID supplied by Spryng or the default business route.
    |
    */
    'route' => env('SPRYNG_SMS_ROUTE', 'business'),

    /*
    |----------------------------------------------------------------------
    | Route
    |----------------------------------------------------------------------
    |
    | Character encoding of the body. Value can be: plain, unicode or auto
    |
    */
    'encoding' => env('SPRYNG_SMS_ENCODING', 'auto'),

    /*
    |----------------------------------------------------------------------
    | Reference
    |----------------------------------------------------------------------
    |
    | A client reference.
    |
    */
    'reference' => env('SPRYNG_SMS_REFERENCE'),
];
