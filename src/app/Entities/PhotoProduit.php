<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\ImageTrait; 

class PhotoProduit extends Entity
{
    protected $casts = [
        'id_produit'      => 'integer',
        'id_photo'        => 'integer',
        'ordre_affichage' => 'integer', 
    ];

    //return l'endroit ou est l'image (url)
    public function getSrc(): string
    {
        $fichier = $this->attributes['nom_fichier'] ?? null;
        return $this->getUrlImage('produits/' . $fichier);
    }

    // renvoie si la photo est principale
    public function estPrincipale(): bool
    {
        return isset($this->attributes['ordre_affichage']) && $this->attributes['ordre_affichage'] == 1;
    }

}