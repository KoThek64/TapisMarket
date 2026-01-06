<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Address extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'user_id' => 'integer',
        'id'      => 'integer',
    ];

    /// Returns the full address
    public function getFullAddress(): string
    {
        $number = !empty($this->attributes['number']) ? $this->attributes['number'] . ' ' : '';
        
        return $number . $this->attributes['street'] . ', ' . 
               $this->attributes['postal_code'] . ' ' . 
               $this->attributes['city'] . ' - ' . 
               strtoupper($this->attributes['country']);
    }

    
}