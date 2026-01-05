<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Adresse;

class AdresseModel extends Model
{
    protected $table            = 'adresse';
    protected $primaryKey       = 'id_adresse';
    protected $returnType       = Adresse::class;

    protected $allowedFields    = [
        'id_utilisateur', 
        'numero',          
        'rue', 
        'code_postal', 
        'ville', 
        'pays',            
        'telephone_contact' 
    ];

    protected $validationRules = [
        'id_utilisateur' => 'required|integer',
        'rue' => 'required|min_length[3]|max_length[255]',
        'code_postal' => 'required|min_length[4]|max_length[10]',
        'ville' => 'required|min_length[2]|max_length[100]',
        'pays' => 'required|min_length[2]|max_length[100]',
        'telephone_contact' => 'permit_empty|min_length[10]|max_length[20]',
    ];

    protected $validationMessages = [
        'telephone_contact' => [
            'min_length' => 'Le numéro de téléphone est trop court.'
        ]
    ];

    // Récupère les adresses d'un utilisateur
    public function getAdressesUtilisateur(int $idUtilisateur)
    {
        return $this->where('id_utilisateur', $idUtilisateur)
                    ->orderBy('id_adresse', 'DESC') 
                    ->findAll();
    }

    // Supprime une adresse d'un utilisateur
    public function supprimerAdresse(int $idAdresse, int $idUtilisateur)
    {
        return $this->where('id_adresse', $idAdresse)
                    ->where('id_utilisateur', $idUtilisateur)
                    ->delete();
    }
}