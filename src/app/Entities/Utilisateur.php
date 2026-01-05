<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\DateTrait;  
use App\Traits\ImageTrait;

class Utilisateur extends Entity
{
    use DateTrait;
    use ImageTrait;

    protected $dates = ['date_inscription'];
    
    

    //Cette fonction crypte mdp
    public function setMotDePasse(string $motDePasse)
    {   
        //On crypte le mdp avant de le mettre dans la base de données
        $this->attributes['mot_de_passe'] = password_hash($motDePasse, PASSWORD_DEFAULT);
        return $this;
    }

    public function getIdentite(): string
    {
        return ucfirst($this->attributes['prenom']) . ' ' . strtoupper($this->attributes['nom']);
    }

    public function estAdmin(): bool
    {
        return isset($this->attributes['role']) && $this->attributes['role'] === 'ADMIN';
    }

    public function estVendeur(): bool
    {
        return isset($this->attributes['role']) && $this->attributes['role'] === 'VENDEUR';
    }

    public function getDateInscription(): string
    {
        return $this->formaterDate($this->date_creation, false); 
    }
}