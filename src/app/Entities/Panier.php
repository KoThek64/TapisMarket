<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;
use App\Traits\PrixTrait;

class Panier extends Entity
{
    use PrixTrait;

    protected $dates = ['date_creation', 'date_modification'];
    
    protected $casts = [
        'id_client' => 'integer',
        'total'     => 'float', 
    ];

    // Pour l'affichage 
    public function getTotalFormate(): string
    {
        return $this->formaterPrix($this->attributes['total'] ?? 0);
    }

    //renvoie si le panier est vide
    public function estVide(): bool
    {
        return empty($this->attributes['total']) || $this->attributes['total'] == 0;
    }

    //regarde si panier abandonne est donc sup pour la bd
    public function estAbandonne(int $heures = 24): bool
    {
        $dateReference = $this->date_modification ?? $this->date_creation;

        if (empty($dateReference)) {
            return false;
        }

        $limite = Time::now()->subHours($heures);

        return $dateReference < $limite;
    }
}