<?php

namespace App\Traits;

trait PrixTrait
{
    public function formaterPrix(float $montant): string
    {
        return number_format($montant, 2, ',', ' ') . ' €';
    }
}