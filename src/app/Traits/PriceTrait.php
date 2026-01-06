<?php

namespace App\Traits;

trait PriceTrait
{
    protected function formatPrice(float $amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' €';
    }
}