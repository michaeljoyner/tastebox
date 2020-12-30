<?php

return [
    'merchant_id' => env('PAYFAST_ID'),
    'merchant_key' => env('PAYFAST_KEY'),
    'passphrase' => env('PAYFAST_PASSPHRASE'),
    'sandbox' => env('PAYFAST_SANDBOX'),


    'return_url' => env('APP_URL') . "/payfast/return",
    'cancel_url' => env('APP_URL') . "/payfast/cancel",
    'notify_url' => env('APP_URL') . "/payfast/notify",
];
