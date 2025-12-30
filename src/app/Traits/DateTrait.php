<?php

namespace App\Traits;

use CodeIgniter\I18n\Time;

trait DateTrait
{
    //fromatter une date
    public function formaterDate($date, bool $avecHeure = true): string
    {
        if (empty($date)) return '-';

        if (!($date instanceof Time)) {
            $date = Time::parse($date);
        }

        $format = $avecHeure ? 'd/m/Y à H:i' : 'd/m/Y';
        return $date->format($format);
    }

    
    public function formaterDateRelative($date): string
    {
        if (empty($date)) return '';

        if (!($date instanceof Time)) {
            $date = Time::parse($date);
        }

        return $date->humanize();
    }
}