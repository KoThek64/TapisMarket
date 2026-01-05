<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PrixTrait;   
use App\Traits\ImageTrait; 

class LignePanier extends Entity
{   
    use PrixTrait;
    use ImageTrait;

    

    protected $casts = [
        'id_ligne'   => 'integer',
        'id_panier'  => 'integer',
        'id_produit' => 'integer',
        'quantite'   => 'integer',
    ];

    //Calvule total 
    public function getSousTotal(): float
    {
        $prix = $this->attributes['prix'] ?? 0.00;
        $qte  = $this->attributes['quantite'] ?? 0;

        return $prix * $qte;
    }
    
    // Affichage format
    public function getSousTotalFormate(): string
    {
        return $this->formaterPrix($this->getSousTotal());
    }

    //
    public function getPrixUnitaireFormate(): string
    {
        return $this->formaterPrix($this->attributes['prix'] ?? 0);
    }

    // retourne le nom du produit 
    public function getNomProduit(): string
    {
        return $this->attributes['titre'] ?? 'Produit #' . $this->id_produit;
    }

    //Pour l'affichaeg de l'image
    public function getImageProduit(): string
    {
        $img = $this->attributes['image'] ?? $this->attributes['nom_fichier'] ?? null;
        
        return $this->getUrlImage($img);
    }

    // Pour pouvoir cliquer sur le produit dans le son panier
    public function getLienProduit(): string
    {
        $alias = $this->attributes['alias'] ?? $this->id_produit;
        return base_url('produit/' . $alias);
    }
}