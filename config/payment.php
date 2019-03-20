<?php

declare(strict_types=1); 

return [
    'gateways' => [
        'wx' => [
            'app_id' => env('WX_APP_ID'),
            'mech_id' => env('WX_MECH_ID'),
            'api_key' => env('WX_API_KEY'),
        ],
        'qpay' => [
            'app_id' => env('QPAY_APP_ID'),
            'app_key' => env('QPAY_APP_KEY'),
            'mech_id' => env('QPAY_MECH_ID'),
            'api_key' => env('QPAY_API_KEY'),
        ],
        'alipay' => [
            'sign_type' => env('ALIPAY_SIGN_TYPE', 'RSA2'),
            'app_id' => env('ALIPAY_APP_ID'),
            'private_key' => env('ALIPAY_PRIVATE_KEY'),
            'alipay_public_key' => env('ALIPAY_PUBLIC_KEY'),
            'notify_url' => '',
            'return_url' => '',
        ]
    ]
];
