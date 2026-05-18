<?php

namespace App\Services;

class CommissionService
{
    // E.g., Admin takes 10% from every sale
    const COMMISSION_RATE = 0.10;

    public function calculate($amount)
    {
        return $amount * self::COMMISSION_RATE;
    }
}
