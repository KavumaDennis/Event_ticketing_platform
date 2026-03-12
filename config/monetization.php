<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Platform Service Fee
    |--------------------------------------------------------------------------
    |
    | This fee is added to every ticket purchase.
    | type: 'fixed' or 'percentage'
    | amount: price in UGX or percentage value
    |
    */
    'service_fee' => [
        'type' => 'percentage',
        'amount' => 5, // 5%
    ],

    /*
    |--------------------------------------------------------------------------
    | Event Boosting Prices
    |--------------------------------------------------------------------------
    |
    | Prices for boosting events in UGX.
    |
    */
    'boosting' => [
        'hot_threshold' => 50,
        '24_hours' => 5000,
        '7_days' => 25000,
        '30_days' => 80000,
    ],

    // Referral System
    'referral_commission_rate' => 0.05, // 5% of base total

    /*
    |--------------------------------------------------------------------------
    | Ranking & Tiers
    |--------------------------------------------------------------------------
    */
    'ranking' => [
        'weights' => [
            'likes' => 10,
            'purchases' => 50,
            'time_decay' => 0.1,
            'boost_multiplier' => 2.0,
        ],
    ],

    'tiers' => [
        'free' => [
            'name' => 'Free',
            'trend_multiplier' => 1.0,
        ],
        'pro' => [
            'name' => 'Pro',
            'trend_multiplier' => 1.5,
        ],
        'elite' => [
            'name' => 'Elite',
            'trend_multiplier' => 2.5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Currencies & FX
    |--------------------------------------------------------------------------
    */
    'supported_currencies' => [
        'UGX',
        'USD',
        'KES',
        'TZS',
        'EUR',
        'GBP',
    ],
    'fx' => [
        'provider' => env('FX_PROVIDER', 'exchangerate.host'),
        'cache_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risk & Fraud Thresholds
    |--------------------------------------------------------------------------
    */
    'fraud' => [
        'max_quantity' => 10,
        'max_total_base' => 1000000,
        'max_purchases_per_day' => 3,
    ],
];
