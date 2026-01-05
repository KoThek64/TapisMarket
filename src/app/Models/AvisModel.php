<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Avis;

class AvisModel extends Model
{
    protected $table            = 'avis';
    protected $primaryKey       = 'id_avis';
    protected $returnType       = Avis::class;

    protected $allowedFields    = [
        'id_produit',
        'id_client',
        'note',
        'commentaire',
        'date_publication',
        'statut_moderation' 
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_publication';
    protected $updatedField  = null;

    protected $validationRules = [
        'id_produit'        => 'required|integer',
        'id_client'         => 'required|integer',
        'note'              => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'commentaire'       => 'required|min_length[5]|max_length[1000]',
        'statut_moderation' => 'in_list[PUBLIE,REFUSE]', 
    ];
    
    protected $validationMessages = [
        'note' => [
            'less_than_equal_to' => 'La note doit être comprise entre 1 et 5.',
        ]
    ];

    //recupere les avis pour les afficher 
    public function getAvisPourProduit(int $idProduit)
    {
        return $this->select('avis.*, utilisateur.prenom, utilisateur.nom')
                    ->join('client', 'client.id_utilisateur = avis.id_client')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->where('avis.id_produit', $idProduit)
                    ->where('avis.statut_moderation', 'PUBLIE') 
                    ->orderBy('avis.date_publication', 'DESC')
                    ->findAll();
    }

    public function getStatsProduit(int $idProduit)
    {
        return $this->select('AVG(note) as note_moyenne, COUNT(id_avis) as nombre') 
                    ->where('id_produit', $idProduit)
                    ->where('statut_moderation', 'PUBLIE')
                    ->first();
    }

    //renvoie si un client a deja note un produit
    public function aDejaNote(int $idProduit, int $idClient): bool
    {
        return $this->where('id_produit', $idProduit)
                    ->where('id_client', $idClient)
                    ->countAllResults() > 0;
    }

    //Permet de moderer un avis
    public function modererAvis(int $idAvis, string $statut)
    {
        return $this->update($idAvis, ['statut_moderation' => $statut]);
    }

    //verifie si un client a achete et recu un produit avant de pouvoir le noter
    public function aAcheteEtRecu(int $idClient, int $idProduit): bool
    {
        return $this->db->table('commande')
            ->join('ligne_commande', 'commande.id_commande = ligne_commande.id_commande')
            ->where('commande.id_client', $idClient)
            ->where('ligne_commande.id_produit', $idProduit)
            ->where('commande.statut', 'LIVREE') 
            ->countAllResults() > 0;
    }
}