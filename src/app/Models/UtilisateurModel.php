<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Utilisateur;

class UtilisateurModel extends Model
{
    protected $table            = 'utilisateur';
    protected $primaryKey       = 'id_utilisateur';

    protected $useAutoIncrement = true;
    protected $returnType       = Utilisateur::class; 
    protected $useSoftDeletes   = false;  
    protected $protectFields    = true;
    protected $allowedFields = [
        'email',
        'mot_de_passe', 
        'nom', 'prenom',
        'date_inscription', 
        'role' 
    ];

    protected $useTimestamps = true;       
    protected $createdField  = 'date_inscription'; 
    protected $updatedField  = null;        

    protected $validationRules = [
        'email'        => 'required|valid_email|is_unique[utilisateur.email,id_utilisateur,{id_utilisateur}]',
        'nom'          => 'required|min_length[2]',
        'prenom'       => 'required|min_length[2]',
        'mot_de_passe' => 'required|min_length[8]', 
        'role'         => 'in_list[ADMIN,VENDEUR,CLIENT]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];


    // Vérifie les informations de connexion 
    public function verifierConnexion(string $email, string $motDePasse)
    {
        $user = $this->where('email', $email)->first();
        if ($user && password_verify($motDePasse, $user->mot_de_passe)) {
            return $user;
        }
        return false;
    }

    // recuperer un user par son email pour mdp oublie normalemnt
    public function getParEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // Récupérer les utilisateurs par rôle
    public function getUtilisateursParRole(string $role, int $perPage = 20)
    {
        return $this->where('role', $role)
                    ->orderBy('date_inscription', 'DESC')
                    ->paginate($perPage);
    }

}