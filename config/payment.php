<?php

return [
    'default_currency' => env('PAYMENT_DEFAULT_CURRENCY', 'NPR'),

    'esewa' => [
        'enabled' => env('ESEWA_ENABLED', true),
        'sandbox' => env('ESEWA_SANDBOX', true),
        'product_code' => env('ESEWA_PRODUCT_CODE', 'EPAYTEST'),
        'secret_key' => env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'),
        'payment_url' => env('ESEWA_PAYMENT_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
        'status_url' => env('ESEWA_STATUS_URL', 'https://rc.esewa.com.np/api/epay/transaction/status/'),
    ],

    'khalti' => [
        'enabled' => env('KHALTI_ENABLED', false),
        'sandbox' => env('KHALTI_SANDBOX', true),
        'secret_key' => env('KHALTI_SECRET_KEY'),
        'base_url' => env('KHALTI_BASE_URL', 'https://dev.khalti.com/api/v2'),
    ],

    'paypal' => [
        'enabled' => env('PAYPAL_ENABLED', false),
        'sandbox' => env('PAYPAL_SANDBOX', true),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'base_url' => env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com'),
        'currency' => env('PAYPAL_CURRENCY', 'USD'),
    ],

    'stripe' => [
        'enabled' => env('STRIPE_ENABLED', false),
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'base_url' => env('STRIPE_BASE_URL', 'https://api.stripe.com/v1'),
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],

    'bank_transfer' => [
        'enabled' => env('BANK_TRANSFER_ENABLED', true),
        'bank_name' => env('BANK_TRANSFER_BANK_NAME', 'Your Bank Name'),
        'account_name' => env('BANK_TRANSFER_ACCOUNT_NAME', 'B Commerce Mart'),
        'account_number' => env('BANK_TRANSFER_ACCOUNT_NUMBER', '0000000000'),
    ],

    'qr_payment' => [
        'enabled' => env('QR_PAYMENT_ENABLED', true),
        'label' => env('QR_PAYMENT_LABEL', 'QR Payment'),
        'instructions' => env('QR_PAYMENT_INSTRUCTIONS', 'Pay with your preferred mobile banking app and keep your transaction reference.'),
    ],
];
