<?php

return [
    'merchant_id' => env('PAYFAST_ID'),
    'merchant_key' => env('PAYFAST_KEY'),
    'passphrase' => env('PAYFAST_PASSPHRASE'),
    'sandbox' => env('PAYFAST_SANDBOX'),
    'ngrok' => env('PAYFAST_NGROK'),


    'return_url' => env('APP_URL') . "/payfast/return",
    'cancel_url' => env('APP_URL') . "/payfast/cancel",
    'notify_url' => env('APP_URL') . "/payfast/notify",

    'fake_payments' => env('PAYFAST_FAKE_PAYMENTS', false),
];
