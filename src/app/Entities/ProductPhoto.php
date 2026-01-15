<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\ImageTrait;

class ProductPhoto extends Entity
{
    use ImageTrait;

    protected $casts = [
        'product_id' => 'integer',
        'photo_id' => 'integer',
        'display_order' => 'integer',
    ];

    // Returns the image URL
    public function getSrc(): string
    {
        $file = $this->attributes['file_name'] ?? null;
        return $this->getImageUrl('products/' . $file);
    }

    // Checks if the photo is the main one
    public function isMain(): bool
    {
        return isset($this->attributes['display_order']) && $this->attributes['display_order'] == 1;
    }

}
