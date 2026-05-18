<?php

namespace App\Helpers;

class CurrencyHelper
{
    protected static $rates = [
        'INR' => 1,
        'USD' => 0.012,
        'EUR' => 0.011,
        'GBP' => 0.0094,
    ];

    protected static $symbols = [
        'INR' => '₹',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
    ];

    public static function format($amount, $currency = null)
    {
        $currency = $currency ?? session('currency', 'INR');
        $rate = self::$rates[$currency] ?? 1;
        $symbol = self::$symbols[$currency] ?? '₹';
        
        $converted = $amount * $rate;
        
        return $symbol . number_format($converted, 2);
    }

    public static function getCurrencySymbol($currency = null)
    {
        $currency = $currency ?? session('currency', 'INR');
        return self::$symbols[$currency] ?? '₹';
    }
}
