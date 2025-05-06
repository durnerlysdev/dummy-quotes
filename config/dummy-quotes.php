<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Base URL of the API
    |--------------------------------------------------------------------------
    |
    | This value defines the base URL of the API that will be used for making
    | requests. Default 'https://dummyjson.com'.
    |
    */
    'base_url' => 'https://dummyjson.com',

    /*
    |--------------------------------------------------------------------------
    | Maximum number of requests allowed per time window
    |--------------------------------------------------------------------------
    |
    | This value sets the maximum number of requests that can be made within
    | a specified time window. Default 60 requests per minute.
    |
    */
    'max_requests' => '60',

    /*
    |--------------------------------------------------------------------------
    | Duration of the time window in seconds
    |--------------------------------------------------------------------------
    |
    | This value defines the duration of the time window in seconds for
    | rate limiting.
    |
    */
    'time_window' => 60,
];
