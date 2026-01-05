<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Client;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id_utilisateur';
    protected $returnType       = Client::class;
    
    protected $useAutoIncrement = false; 

    protected $allowedFields    = ['id_utilisateur', 'telephone', 'date_naissance'];

    protected $validationRules = [
        'id_utilisateur' => 'required|integer|is_unique[client.id_utilisateur]',
        'telephone'      => 'permit_empty|min_length[10]|max_length[20]',
        'date_naissance' => 'permit_empty|valid_date',
    ];

    protected $validationMessages = [
        'telephone' => [
            'min_length' => 'Le numéro de téléphone est trop court.'
        ]
    ];

    // REnvoie le client pour pouvoir affiche un message personnalisé si connecte 
    public function getProfilComplet(int $id)
    {
        return $this->select('client.*, utilisateur.nom, utilisateur.prenom, utilisateur.email, utilisateur.date_inscription')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->find($id);
    }


    //Bonus pour l'admin surson tableau de bord on pourrait voir les derniers inscrits
    public function getDerniersInscrits(int $limit = 5)
    {
        return $this->select('client.*, utilisateur.nom, utilisateur.prenom, utilisateur.date_inscription')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = client.id_utilisateur')
                    ->orderBy('utilisateur.date_inscription', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

}