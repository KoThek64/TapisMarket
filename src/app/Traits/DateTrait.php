<?php

namespace App\Traits;

use CodeIgniter\I18n\Time;

trait DateTrait
{
    // Format a date
    protected function formatDate($date, bool $withTime = true): string
    {
        if (empty($date)) return '-';

        if (!($date instanceof Time)) {
            $date = Time::parse($date);
        }

        $format = $withTime ? 'd/m/Y at H:i' : 'd/m/Y';
        return $date->format($format);
    }

    
    protected function formaterDateRelative($date): string
    {
        if (empty($date)) return '';

        if (!($date instanceof Time)) {
            $date = Time::parse($date);
        }

        return $date->humanize();
    }
}