<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PriceTrait; 
use App\Traits\ImageTrait;
use App\Traits\RatingTrait;

class Product extends Entity
{
    use PriceTrait;
    use ImageTrait;
    use RatingTrait;

    protected $dates = [
        'created_at', 
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'id'               => 'integer',
        'price'            => 'float',
        'stock_available'  => 'integer',
        'seller_id'        => 'integer',
        'category_id'      => 'integer',
        'average_rating'   => 'float',
    ];

    //Check if product is available
    public function isAvailable(): bool
    {
        return (
            $this->attributes['product_status'] === 'APPROVED' && 
            $this->attributes['stock_available'] > 0
        );
    }

    public function isPurchasable(): bool
    {
        return $this->isAvailable();
    }

    //Bonus function to warn sellers of low stock
    public function needsStock(): bool
    {
        return $this->stock_available <= 3;
    }

    //For price display
    public function getFormattedPrice(): string
    {
        return $this->formatPrice($this->attributes['price']);
    }

    //Returns product link
    public function getLink(): string
    {
        if (!empty($this->attributes['alias'])) {
            return base_url('product/' . $this->attributes['alias']);
        }
        return base_url('product/' . $this->attributes['id']);
    }

    public function getImage(): string
    {
        $img = $this->attributes['image'] ?? $this->attributes['file_name'] ?? null;
        
        return $this->getUrlImage($img);
    }

    //Calculate average rating for display
    public function getAverageRating(): float
    {
        return round($this->attributes['average_rating'] ?? 0, 1);
    }

    public function getStars(): string
    {
        return $this->generateRatingHtml($this->attributes['average_rating'] ?? 0);
    }
}