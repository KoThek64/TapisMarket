<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Administrateur;

class AdministrateurModel extends Model
{
    protected $table            = 'administrateur';
    protected $primaryKey       = 'id_utilisateur'; 
    protected $returnType       = Administrateur::class;

    protected $useAutoIncrement = false;

    protected $allowedFields    = ['id_utilisateur'];

    protected $validationRules  = [
        'id_utilisateur' => 'required|integer|is_unique[administrateur.id_utilisateur]',
    ];
    
    protected $validationMessages = [
        'id_utilisateur' => [
            'is_unique' => 'Cet utilisateur est déjà un administrateur.'
        ]
    ];

    //recuper le profil complet d'un admin grace a l'id
    public function getProfilAdmin(int $id)
    {
        return $this->select('administrateur.*, utilisateur.nom, utilisateur.prenom, utilisateur.email, utilisateur.date_inscription')
                    ->join('utilisateur', 'utilisateur.id_utilisateur = administrateur.id_utilisateur')
                    ->find($id);
    }
}