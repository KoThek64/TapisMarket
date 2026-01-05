<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\LigneCommande;

class LigneCommandeModel extends Model
{
    protected $table            = 'ligne_commande';
    protected $primaryKey       = 'id_ligne_commande';
    protected $returnType       = LigneCommande::class;

    protected $allowedFields    = [
        'id_ligne_commande', 
        'id_commande', 
        'id_produit', 
        'quantite', 
        'prix_unitaire'
    ];

    protected $validationRules = [
        'quantite'      => 'required|integer|greater_than[0]',
        'prix_unitaire' => 'required|decimal|greater_than_equal_to[0]',
    ];

    // Liste des ventes 
    public function getVentesVendeur(int $idVendeur, int $perPage = 10)
    {
        return $this->select('
                        ligne_commande.*, 
                        produit.titre, 
                        produit.alias,
                        commande.date_commande, 
                        commande.statut, 
                        commande.reference,
                        commande.adresse_liv_rue, 
                        commande.adresse_liv_cp, 
                        commande.adresse_liv_ville, 
                        commande.adresse_liv_pays,
                        utilisateur.nom as nom_client, 
                        utilisateur.prenom as prenom_client
                    ')
                    ->join('produit', 'produit.id_produit = ligne_commande.id_produit')
                    ->join('commande', 'commande.id_commande = ligne_commande.id_commande')
                    ->join('client', 'client.id_utilisateur = commande.id_client')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->where('produit.id_vendeur', $idVendeur)
                    ->where('commande.statut !=', 'ANNULEE')
                    ->orderBy('commande.date_commande', 'DESC')
                    ->paginate($perPage);
    }

    //Compteur vente pour les vendeurs
    public function countVentesVendeur(int $idVendeur): int
    {
        return $this->join('produit', 'produit.id_produit = ligne_commande.id_produit')
                    ->join('commande', 'commande.id_commande = ligne_commande.id_commande')
                    ->where('produit.id_vendeur', $idVendeur)
                    ->where('commande.statut !=', 'ANNULEE')
                    ->countAllResults();
    }

    //chiffre affaire vendeur
    public function getChiffreAffairesVendeur(int $idVendeur): float
    {
        $statutsValides = ['PAYEE', 'EN_PREPARATION', 'EXPEDIEE', 'LIVREE'];

        $result = $this->selectSum('ligne_commande.prix_unitaire * ligne_commande.quantite', 'total_ca')
                       ->join('produit', 'produit.id_produit = ligne_commande.id_produit')
                       ->join('commande', 'commande.id_commande = ligne_commande.id_commande')
                       ->where('produit.id_vendeur', $idVendeur)
                       ->whereIn('commande.statut', $statutsValides)
                       ->first();

        return $result->total_ca ?? 0.00;
    }

    //Les produits les plus vendus de ce vendeuron prend trois meiller mais on pourrait changer
    public function getBestSellersVendeur(int $idVendeur, int $limit = 3)
    {
        return $this->select('produit.titre, SUM(ligne_commande.quantite) as total_vendus')
                    ->join('produit', 'produit.id_produit = ligne_commande.id_produit')
                    ->join('commande', 'commande.id_commande = ligne_commande.id_commande')
                    ->where('produit.id_vendeur', $idVendeur)
                    ->where('commande.statut !=', 'ANNULEE')
                    ->groupBy('ligne_commande.id_produit')
                    ->orderBy('total_vendus', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }


    // Pour page detail commande
    public function getLignesDuneCommande(int $idCommande)
    {
        return $this->select('ligne_commande.*, produit.titre, produit.alias, photo_produit.nom_fichier as image')
                    ->join('produit', 'produit.id_produit = ligne_commande.id_produit')
                    ->join('photo_produit', 'photo_produit.id_produit = produit.id_produit AND photo_produit.ordre_affichage = 1', 'left')
                    ->where('id_commande', $idCommande)
                    ->findAll();
    }
}