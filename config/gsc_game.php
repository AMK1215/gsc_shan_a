<?php

return [

    'api' => [
        'operator_code' => env('GSC_SEAMLESS_OPERATOR_ID'),  // Using SEAMLESS_OPERATOR_ID from .env
        'password' => env('GSC_SEAMLESS_PASSWORD'),          // Using SEAMLESS_PASSWORD from .env (if required)
        'secret_key' => env('GSC_SEAMLESS_SECRET_KEY'),      // Using SEAMLESS_SECRET_KEY from .env
        'url' => env('GSC_SEAMLESS_API_URL'),                // Using SEAMLESS_API_URL from .env
       // 'currency' => env('SEAMLESS_CURRENCY'),          // Using SEAMLESS_CURRENCY from .env
    ],

];