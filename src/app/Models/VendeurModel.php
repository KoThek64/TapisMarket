<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Vendeur;

class VendeurModel extends Model
{
    protected $table            = 'vendeur';
    protected $primaryKey       = 'id_utilisateur';
    protected $returnType       = Vendeur::class;

    protected $useAutoIncrement = false; 
    
    protected $allowedFields    = [
        'id_utilisateur', 
        'nom_boutique', 
        'description_boutique', 
        'siret', 
        'statut', 
        'motif_refus'
    ];

    protected $validationRules = [
        'nom_boutique' => 'required|min_length[3]',
        'siret'        => 'required|exact_length[14]|is_unique[vendeur.siret,id_utilisateur,{id_utilisateur}]',
        'statut'       => 'in_list[EN_ATTENTE_VALIDATION,VALIDE,REFUSE,SUSPENDU]',
    ];

    protected $validationMessages = [
        'siret' => [
            'is_unique' => 'Ce numéro de SIRET est déjà enregistré sur la plateforme.'
        ]
    ];


    // Récupère le profil complet d'un vendeur avec les infos utilisateur
    public function getProfilComplet(int $idUtilisateur)
    {
        return $this->select('vendeur.*, utilisateur.email, utilisateur.nom, utilisateur.prenom, utilisateur.date_inscription')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = vendeur.id_utilisateur')
                    ->find($idUtilisateur);
    }


    // Récupère les vendeurs en attent
    public function getVendeursEnAttente(int $perPage = 20)
    {
        return $this->select('vendeur.id_utilisateur, vendeur.nom_boutique, vendeur.siret, vendeur.statut, utilisateur.date_inscription, utilisateur.email')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = vendeur.id_utilisateur') 
                    ->where('statut', 'EN_ATTENTE_VALIDATION')
                    ->orderBy('utilisateur.date_inscription', 'ASC')
                    ->paginate($perPage);
    }

    //valider vendeur
    public function validerVendeur(int $idVendeur)
    {
        return $this->update($idVendeur, [
            'statut' => 'VALIDE',
            'motif_refus' => null
        ]);
    }

    // refuser un vendeur avec un motif
    public function refuserVendeur(int $idVendeur, string $motif)
    {
        return $this->update($idVendeur, [
            'statut' => 'REFUSE',
            'motif_refus' => $motif
        ]);
    }
}