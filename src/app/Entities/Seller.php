<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Seller extends Entity
{
    protected $dates = [
        'created_at',
        'deleted_at'
    ];
    
    // Automatic conversion
    protected $casts   = [
        'user_id' => 'integer',
    ];

    // Check if seller can perform actions
    public function isActive(): bool
    {
        return $this->attributes['status'] === SELLER_VALIDATED;
    }

    // Check if seller is waiting for admin validation
    public function isPending(): bool
    {
        return $this->attributes['status'] === SELLER_PENDING;
    }

    // Returns formatted SIRET (e.g., 123 456 789 00012)
    public function getFormattedSiret(): string
    {
        $siret = $this->attributes['siret'] ?? '';
        
        if (strlen($siret) === 14) {
            return substr($siret, 0, 3) . ' ' . substr($siret, 3, 3) . ' ' . substr($siret, 6, 3) . ' ' . substr($siret, 9, 5);
        }
        
        return $siret;
    }
}