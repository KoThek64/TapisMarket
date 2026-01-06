<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Customer extends Entity
{
    protected $dates = ['birth_date'];

    protected $casts = [
        'user_id' => 'integer',
    ];

    // Format phone number for display
    public function getFormattedPhone(): string
    {
        $tel = $this->attributes['phone'] ?? null;

        if (empty($tel)) {
            return '<span style="color:#ccc; font-style:italic;">Non renseigné</span>';
        }

        $tel = str_replace(' ', '', $tel);

        return implode(' ', str_split($tel, 2));
    }

    /// Get identity
    public function getIdentity(): string
    {
        if (isset($this->attributes['firstname']) && isset($this->attributes['lastname'])) {
            return ucfirst($this->attributes['firstname']) . ' ' . strtoupper($this->attributes['lastname']);
        }
        return 'Client #' . ($this->attributes['user_id'] ?? '');
    }
}