<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Panier;
use CodeIgniter\I18n\Time;

class PanierModel extends Model
{
    protected $table            = 'panier';
    protected $primaryKey       = 'id_panier';
    protected $useAutoIncrement = true;
    protected $returnType       = Panier::class;

    protected $allowedFields    = [
        'id_client', 
        'date_creation',
        'date_modification', 
        'total'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_creation';
    protected $updatedField  = 'date_modification'; 

    // recupere ou cree un panier pour le client
    public function getPanierActif(int $idClient)
    {
        $panier = $this->where('id_client', $idClient)->first();

        if (!$panier) {
            $this->insert([
                'id_client' => $idClient,
                'total' => 0,
                'date_creation' => Time::now()->toDateTimeString()
            ]);
            return $this->find($this->getInsertID());
        }
        return $panier;
    }

    // recupere le contenu
    public function getArticlesPanier($idPanier)
    {
        return $this->db->table('ligne_panier')
                        ->select('ligne_panier.*, produit.prix, produit.titre, produit.alias, photo_produit.nom_fichier as image')
                        ->join('produit', 'produit.id_produit = ligne_panier.id_produit')
                        ->join('photo_produit', 'photo_produit.id_produit = produit.id_produit AND photo_produit.ordre_affichage = 1', 'left')
                        ->where('ligne_panier.id_panier', $idPanier)
                        ->get()
                        ->getResult(\App\Entities\LignePanier::class); 
    }
   
    // recalcule le total 
    public function mettreAJourTotal(int $idPanier)
    {
        $resultat = $this->db->table('ligne_panier')
                             ->select('SUM(produit.prix * ligne_panier.quantite) as total_calcule')
                             ->join('produit', 'produit.id_produit = ligne_panier.id_produit')
                             ->where('ligne_panier.id_panier', $idPanier)
                             ->get()
                             ->getRow();

        $nouveauTotal = $resultat->total_calcule ?? 0;

        $this->update($idPanier, ['total' => $nouveauTotal]);
    }


    //pour vider panier
    public function viderPanier(int $idPanier)
    {
        $this->db->table('ligne_panier')->where('id_panier', $idPanier)->delete();
        
        return $this->update($idPanier, [
            'total' => 0,
            'date_modification' => Time::now()->toDateTimeString()
        ]);
    }

     public function supprimerVieuxPaniers(int $jours = 30)
    {
        $dateLimite = date('Y-m-d H:i:s', strtotime("-{$jours} days"));

        return $this->where('date_modification <', $dateLimite)
                    ->delete();
    }
}