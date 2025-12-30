<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\LignePanier;

class LignePanierModel extends Model
{
    protected $table            = 'ligne_panier';
    protected $primaryKey       = 'id_ligne'; 
    protected $useAutoIncrement = true;       
    protected $returnType       = LignePanier::class;

    protected $allowedFields    = ['id_ligne', 'id_panier', 'id_produit', 'quantite'];

    protected $validationRules  = [
        'quantite' => 'required|integer|greater_than[0]',
    ];

    public function ajouterArticle($idPanier, $idProduit, $quantite)
    {
        $ligne = $this->where('id_panier', $idPanier)
                      ->where('id_produit', $idProduit)
                      ->first();

        if ($ligne) {
            $ligne->quantite += $quantite;
            return $this->save($ligne); 
        } else {
            return $this->insert([
                'id_panier'  => $idPanier,
                'id_produit' => $idProduit,
                'quantite'   => $quantite
            ]);
        }
    }

    // Définit la quantité exacte 
    public function modifierQuantite($idPanier, $idProduit, $nouvelleQuantite)
    {
        if ($nouvelleQuantite <= 0) {
            return $this->supprimerArticle($idPanier, $idProduit);
        }

        return $this->where('id_panier', $idPanier)
                    ->where('id_produit', $idProduit)
                    ->set(['quantite' => $nouvelleQuantite])
                    ->update();
    }

    // Retire un produit du panier
    public function supprimerArticle($idPanier, $idProduit)
    {
        return $this->where('id_panier', $idPanier)
                    ->where('id_produit', $idProduit)
                    ->delete();
    }

    // Renvoie le nombre total d'articles pour le header
    public function getNombreArticlesTotal($idPanier): int
    {
        $result = $this->selectSum('quantite')
                       ->where('id_panier', $idPanier)
                       ->first();

        return (int) ($result->quantite ?? 0);
    }
}