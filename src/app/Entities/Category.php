<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\ImageTrait;

class Category extends Entity
{   
    use ImageTrait;

    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id' => 'integer',
    ];

    // Default image
    // The function works but needs review
    public function getImage(): string
    {
        return $this->getImageUrl($this->attributes['image_url'] ?? null);
    }

    public function getLink(): string
    {
        if (!empty($this->attributes['alias'])) {
            return base_url('category/' . $this->attributes['alias']);
        }
        
        return base_url('category/' . $this->attributes['id']);
    }
    
}