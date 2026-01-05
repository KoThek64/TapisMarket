<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Adresse extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_utilisateur' => 'integer',
        'id_adresse'     => 'integer',
    ];

    ///renvoie l'adresse
    public function getAdresseComplete(): string
    {
        $numero = !empty($this->attributes['numero']) ? $this->attributes['numero'] . ' ' : '';
        
        return $numero . $this->attributes['rue'] . ', ' . 
               $this->attributes['code_postal'] . ' ' . 
               $this->attributes['ville'] . ' - ' . 
               strtoupper($this->attributes['pays']);
    }

    
}