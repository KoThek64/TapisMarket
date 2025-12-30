<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\DateTrait;
use App\Traits\PrixTrait;

class Commande extends Entity
{
    use DateTrait;
    use PrixTrait;

    protected $dates = ['date_commande'];

    // Conversion
    protected $casts = [
        'id_commande' => 'integer',
        'id_client'   => 'integer',
        'total_ttc'   => 'float',
        'frais_port'  => 'float',
    ];

    //Format des prix
    public function getTotalFormate(): string
    {
        return $this->formaterPrix($this->getTotal());
    }

    //Regarde si le commande est termine pour la mettre dans l'hostorique
    public function estTerminee(): bool
    {
        return in_array($this->attributes['statut'], ['LIVREE', 'ANNULEE']);
    }

    //fonction our faire jolie 
    public function getCouleurStatut(): string
    {
        return match ($this->attributes['statut']) {
            'EN_COURS_VALIDATION' => 'bg-warning', 
            'PAYEE'          => 'bg-info',
            'EN_PREPARATION' => 'bg-secondary', 
            'EXPEDIEE'       => 'bg-primary',
            'LIVREE'         => 'bg-success',
            'ANNULEE'        => 'bg-danger',
            default          => 'bg-light',
        };
    }

    // Formate la date 
    public function getDateAffichee(): string
    {
        return $this->formaterDate($this->date_commande);
    }

    //Adresse de livraison pour la mettre dans la BD
    public function getAdresseLivraisonComplete(): string
    {
        return $this->attributes['adresse_liv_rue'] . ', ' . 
               $this->attributes['adresse_liv_cp'] . ' ' . 
               $this->attributes['adresse_liv_ville'] . ' (' . 
               strtoupper($this->attributes['adresse_liv_pays']) . ')';
    }
}