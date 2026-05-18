<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function setLocale($locale)
    {
        if (in_array($locale, ['en', 'es', 'fr', 'hi'])) {
            session(['locale' => $locale]);
        }
        return back();
    }

    public function setCurrency($currency)
    {
        if (in_array($currency, ['INR', 'USD', 'EUR', 'GBP'])) {
            session(['currency' => $currency]);
        }
        return back();
    }
}
