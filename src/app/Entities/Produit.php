<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PrixTrait; 
use App\Traits\ImageTrait;

class Produit extends Entity
{
    use PrixTrait;
    use ImageTrait;

    protected $dates = ['date_creation', 'date_modification'];

    protected $casts = [
        'id_produit'       => 'integer',
        'prix'             => 'float',
        'stock_disponible' => 'integer',
        'id_vendeur'       => 'integer',
        'id_categorie'     => 'integer',
    ];

    //On verifie si un produit est disponible
    public function estDisponible(): bool
    {
        return (
            $this->attributes['statut_produit'] === 'APPROUVE' && 
            $this->attributes['stock_disponible'] > 0
        );
    }

    public function estAchetable(): bool
    {
        return $this->estDisponible();
    }

    //Fonction un peu bonus a utiliser si on veut prevenir les vendeur de leurs stocks bas
    public function aBesoinDeStock(): bool
    {
        return $this->stock_disponible <= 3;
    }

    //Pour l'affiche du prix 
    public function getPrixFormate(): string
    {
        return $this->formaterPrix($this->attributes['prix']);
    }

    //Renvoie le lien du produit
    public function getLien(): string
    {
        if (!empty($this->attributes['alias'])) {
            return base_url('produit/' . $this->attributes['alias']);
        }
        return base_url('produit/' . $this->attributes['id_produit']);
    }

    public function getImage(): string
    {
        $img = $this->attributes['image'] ?? $this->attributes['nom_fichier'] ?? null;
        
        return $this->getUrlImage($img);
    }

    //Calcul la note moyenne d'un produit pour l'affichg
    public function getNoteMoyenne(): float
    {
        return round($this->attributes['note_moyenne'] ?? 0, 1);
    }

    
}