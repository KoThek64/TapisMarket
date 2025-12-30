<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Client extends Entity
{
    protected $dates = ['date_naissance'];

    protected $casts = [
        'id_utilisateur' => 'integer',
    ];

    //Formate l'affichage
    public function getTelephoneAffiche(): string
    {
        $tel = $this->attributes['telephone'] ?? null;

        if (empty($tel)) {
            return '<span style="color:#ccc; font-style:italic;">Non renseigné</span>';
        }

        $tel = str_replace(' ', '', $tel);

        return implode(' ', str_split($tel, 2));
    }

    /// son nom 
    public function getIdentite(): string
    {
        if (isset($this->attributes['prenom']) && isset($this->attributes['nom'])) {
            return ucfirst($this->attributes['prenom']) . ' ' . strtoupper($this->attributes['nom']);
        }
        return 'Client #' . ($this->attributes['id_utilisateur'] ?? '');
    }
}