<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Commande;

class CommandeModel extends Model
{
    protected $table            = 'commande';
    protected $primaryKey       = 'id_commande';
    protected $returnType       = Commande::class;

    protected $allowedFields    = [
        'id_client',
        'reference',
        'date_commande', 
        'statut',
        'mode_livraison', 
        'adresse_liv_rue', 
        'adresse_liv_cp', 
        'adresse_liv_ville', 
        'adresse_liv_pays', 
        'total_ttc', 
        'frais_port'
    ];

    protected $beforeInsert = ['genererReference'];

    protected $validationRules = [
        'total_ttc' => 'required|decimal|greater_than[0]',
        'statut'    => 'in_list[EN_COURS_VALIDATION,PAYEE,EN_PREPARATION,EXPEDIEE,LIVREE,ANNULEE]',
        'reference' => 'is_unique[commande.reference]', 
    ];


    // Historique des commandes
    public function getHistoriqueClient(int $idClient)
    {
        return $this->where('id_client', $idClient)
                    ->orderBy('date_commande', 'DESC')
                    ->findAll();
    }

    // Retrouver une commande par sa référence (ex: CMD-2023-XYZ)
    public function getParReference(string $reference)
    {
        return $this->where('reference', $reference)->first();
    }

    // Détails d'une commande avec les infos du client
    public function getCommandeAvecIdentite(int $idCommande)
    {
        return $this->select('commande.*, utilisateur.nom, utilisateur.prenom, utilisateur.email, client.telephone')
                    ->join('client', 'client.id_utilisateur = commande.id_client')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->find($idCommande);
    }

    // Pour les stats de l'admin
    public function getCommandesEnCours()
    {
        $statutsEnCours = ['EN_COURS_VALIDATION', 'PAYEE', 'EN_PREPARATION', 'EXPEDIEE'];
        return $this->whereIn('statut', $statutsEnCours)
                    ->orderBy('date_commande', 'DESC')
                    ->findAll();
    }

    // Total des ventes
    public function getTotalVentes(): float
    {
        $result = $this->selectSum('total_ttc')
                        ->whereIn('statut', ['PAYEE', 'EN_PREPARATION', 'EXPEDIEE', 'LIVREE'])
                        ->first();
        return $result->total_ttc ?? 0.0;
    }

    // Nombre de commandes validées pour les vendeurs
    public function getNombreCommandes(): int
    {
        return $this->whereIn('statut', ['PAYEE', 'EN_PREPARATION', 'EXPEDIEE', 'LIVREE'])
                    ->countAllResults();
    }

    // Dernières commandes pour le vendeur
    public function getCommandesRecente(int $limit = 5)
    {
        return $this->select('commande.*, utilisateur.nom, utilisateur.prenom') 
                    ->join('client', 'client.id_utilisateur = commande.id_client')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->orderBy('date_commande', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    //pour affichgae 
    public function getStatutCommandes(): array
    {
        return [
            'EN_COURS_VALIDATION' => 'En cours de validation',
            'PAYEE'               => 'Payée',
            'EN_PREPARATION'      => 'En préparation',
            'EXPEDIEE'            => 'Expédiée',
            'LIVREE'              => 'Livrée',
            'ANNULEE'             => 'Annulée',
        ];
    }


    protected function genererReference(array $data)
    {
        if (!isset($data['data']['reference'])) {
            $ref = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $data['data']['reference'] = $ref;
        }
        return $data;
    }
    
}