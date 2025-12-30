<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Vendeur extends Entity
{
    protected $dates = ['date_creation'];
    //converstion automatique 
    protected $casts   = [
        'id_utilisateur' => 'integer',
    ];

    // Verifie si le vendeur peut faire des actions 
    public function estActif(): bool
    {
        return $this->attributes['statut'] === 'VALIDE';
    }

    // Verifie si le vendeur attend validation de l'admin
    public function estEnAttente(): bool
    {
        return $this->attributes['statut'] === 'EN_ATTENTE_VALIDATION';
    }

    // renvoie un format de siret (ex: 123 456 789 00012)
    public function getSiretFormate(): string
    {
        $siret = $this->attributes['siret'] ?? '';
        
        if (strlen($siret) === 14) {
            return substr($siret, 0, 3) . ' ' . substr($siret, 3, 3) . ' ' . substr($siret, 6, 3) . ' ' . substr($siret, 9, 5);
        }
        
        return $siret;
    }
}