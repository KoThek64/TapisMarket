<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use App\Traits\PriceTrait;

class Cart extends Entity
{
    use PriceTrait;

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'customer_id' => 'integer',
        'total' => 'float',
    ];

    // For display 
    public function getFormattedTotal(): string
    {
        return $this->formatPrice($this->attributes['total'] ?? 0);
    }

    // Returns if cart is empty
    public function isEmpty(): bool
    {
        return empty($this->attributes['total']) || $this->attributes['total'] == 0;
    }

    // Checks if cart is abandoned and thus deleted for DB
    public function isAbandoned(int $hours = 24): bool
    {
        $referenceDate = $this->updated_at ?? $this->created_at;

        if (empty($referenceDate)) {
            return false;
        }

        $limit = Time::now()->subHours($hours);

        return $referenceDate < $limit;
    }
}
