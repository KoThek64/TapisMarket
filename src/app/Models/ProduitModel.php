<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Produit;

class ProduitModel extends Model
{
    protected $table            = 'produit';
    protected $primaryKey       = 'id_produit';
    protected $returnType       = Produit::class;

    protected $allowedFields = [
        'id_vendeur', 'id_categorie', 'titre', 'alias',
        'description_courte', 'description_longue', 'prix',
        'stock_disponible', 'dimensions', 'matiere',
        'statut_produit', 'date_creation', 'motif_refus'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_creation';
    protected $updatedField  = null;

    protected $beforeInsert = ['genererAlias'];
    protected $beforeUpdate = ['genererAlias'];

    protected $validationRules = [
        'titre'            => 'required|min_length[3]|max_length[150]',
        'prix'             => 'required|decimal|greater_than[0]',
        'stock_disponible' => 'required|integer|greater_than_equal_to[0]',
        'id_categorie'     => 'required|integer',
    ];



    // Page produit
    public function getParAlias(string $alias)
    {
        return $this->select('produit.*, categorie.nom as nom_categorie, vendeur.nom_boutique')
                    ->join('categorie', 'categorie.id_categorie = produit.id_categorie')
                    ->join('vendeur', 'vendeur.id_utilisateur = produit.id_vendeur')
                    ->where('produit.alias', $alias)
                    ->where('produit.statut_produit', 'APPROUVE')
                    ->first();
    }

    // Liste par catégorie
    public function getParCategorie(int $idCategorie, string $tri = 'recent', int $perPage = 12)
    {
       
        $tris = [
            'prix_asc'  => 'prix ASC',
            'prix_desc' => 'prix DESC',
            'recent'    => 'date_creation DESC'
        ];

        return $this->select('produit.*, photo_produit.nom_fichier as image')
                    ->join('photo_produit', 'photo_produit.id_produit = produit.id_produit AND photo_produit.ordre_affichage = 1', 'left')
                    ->where('statut_produit', 'APPROUVE')
                    ->where('id_categorie', $idCategorie)
                    ->orderBy($tris[$tri] ?? 'date_creation DESC')
                    ->paginate($perPage);
    }

    // Recherche
    public function rechercher(string $terme, int $perPage = 12)
    {
        return $this->select('produit.*, photo_produit.nom_fichier as image')
                    ->join('photo_produit', 'photo_produit.id_produit = produit.id_produit AND photo_produit.ordre_affichage = 1', 'left')
                    ->groupStart() 
                        ->like('titre', $terme)
                        ->orLike('description_courte', $terme)
                    ->groupEnd()
                    ->where('statut_produit', 'APPROUVE')
                    ->paginate($perPage);
    }


    //liste les produits vendeurs
    public function getProduitsVendeur(int $idVendeur, int $perPage = 10)
    {
        return $this->select('produit.*, categorie.nom as categorie_nom')
                    ->join('categorie', 'categorie.id_categorie = produit.id_categorie')
                    ->where('id_vendeur', $idVendeur)
                    ->orderBy('date_creation', 'DESC')
                    ->paginate($perPage);
    }


    public function decrementerStock(int $idProduit, int $qte)
    {
        return $this->where('id_produit', $idProduit)
                    ->decrement('stock_disponible', $qte);
    }

    public function incrementerStock(int $idProduit, int $qte)
    {
        return $this->where('id_produit', $idProduit)
                    ->increment('stock_disponible', $qte);
    }

    public function verifierStockSuffisant(int $idProduit, int $quantiteDemandee): bool
    {
        $produit = $this->select('stock_disponible')
                        ->where('id_produit', $idProduit)
                        ->first();

        if (!$produit) {
            return false; 
        }

        return $produit->stock_disponible >= $quantiteDemandee;
    }

   

    //pou l'admin les produits en attentes de validation
    public function getProduitsEnAttente()
    {
        return $this->select('produit.id_produit, produit.titre, produit.prix, produit.date_creation, vendeur.nom_boutique')
                    ->join('vendeur', 'vendeur.id_utilisateur = produit.id_vendeur')
                    ->where('produit.statut_produit', 'EN_ATTENTE_VALIDATION')
                    ->findAll();
    }

    public function getProduitsAvecImage(int $limit = 6)
{
    return $this->select('produit.*, photo_produit.nom_fichier as image')
                ->join('photo_produit', 'photo_produit.id_produit = produit.id_produit AND photo_produit.ordre_affichage = 1', 'left')
                ->where('produit.statut_produit', 'APPROUVE')
                ->orderBy('produit.date_creation', 'DESC')
                ->findAll($limit); 
}

    
    //Cree l'alias a partir du titre
    protected function genererAlias(array $data)
    {
        if (isset($data['data']['titre']) && empty($data['data']['alias'])) {
            $data['data']['alias'] = url_title($data['data']['titre'], '-', true);
        }
        return $data;
    }
}