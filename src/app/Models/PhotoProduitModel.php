<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\PhotoProduit;

class PhotoProduitModel extends Model
{
    protected $table            = 'photo_produit';
    protected $primaryKey       = 'id_photo';
    protected $returnType       = PhotoProduit::class;

    protected $allowedFields    = ['id_produit', 'nom_fichier', 'ordre_affichage'];



    // recupere toutes les images pourla fiche produit
    public function getGalerie(int $idProduit)
    {
        return $this->where('id_produit', $idProduit)
                    ->orderBy('ordre_affichage', 'ASC')
                    ->findAll();
    }

    
    // recupere l'image de couverture
    public function getImagePrincipale(int $idProduit)
    {
        return $this->where('id_produit', $idProduit)
                    ->orderBy('ordre_affichage', 'ASC') 
                    ->first();
    }

    
    // defini un image principale
    public function definirPrincipale(int $idPhoto, int $idProduit)
    {
        
        $this->where('id_produit', $idProduit)
             ->set(['ordre_affichage' => 2])
             ->update();

        return $this->update($idPhoto, ['ordre_affichage' => 1]);
    }
    
    // Supprime toutes les photos d'un produit 
    public function supprimerTout(int $idProduit)
    {
        return $this->where('id_produit', $idProduit)->delete();
    }

    
}