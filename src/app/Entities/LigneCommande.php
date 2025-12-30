<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PrixTrait;

class LigneCommande extends Entity
{
    use PrixTrait;

    protected $datamap = [];
    protected $dates   = [];
    
    protected $casts   = [
        'id_ligne_commande'=> 'integer',
        'id_commande'   => 'integer',
        'id_produit'    => 'integer',
        'quantite'      => 'integer',
        'prix_unitaire' => 'float',
    ];

    //Total 
    public function getTotal(): float
    {
        return $this->attributes['prix_unitaire'] * $this->attributes['quantite'];
    }

    public function getTotalFormate(): string
    {
        return $this->formaterPrix($this->getTotal());
    }
}