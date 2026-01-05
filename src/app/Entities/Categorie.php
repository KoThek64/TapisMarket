<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\ImageTrait;

class Categorie extends Entity
{   
    use ImageTrait;

    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_categorie' => 'integer',
    ];

    //image par default
    //La fonction marche mais elle est a revoir 
    public function getImage(): string
    {
        return $this->getUrlImage($this->attributes['image_url'] ?? null);
    }

    public function getLien(): string
    {
        if (!empty($this->attributes['alias'])) {
            return base_url('categorie/' . $this->attributes['alias']);
        }
        
        return base_url('categorie/' . $this->attributes['id_categorie']);
    }
    
}