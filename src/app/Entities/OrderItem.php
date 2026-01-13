<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PriceTrait;

class OrderItem extends Entity
{
    use PriceTrait;

    protected $datamap = [];
    protected $dates   = [];
    
    protected $casts   = [
        'id'            => 'integer',
        'order_id'      => 'integer',
        'product_id'    => 'integer',
        'quantity'      => 'integer',
        'unit_price'    => 'float',
    ];

    //Total 
    public function getTotal(): float
    {
        return $this->attributes['unit_price'] * $this->attributes['quantity'];
    }

    public function getFormattedTotal(): string
    {
        return $this->formatPrice($this->getTotal());
    }

    public function getImage(): string
    {
        $filename = $this->attributes['image'] ?? null;
        $productId = $this->attributes['product_id'] ?? null;

        if (empty($filename) || $filename === 'default.jpg' || empty($productId)) {
            return defined('DEFAULT_PRODUCT_IMAGE') ? DEFAULT_PRODUCT_IMAGE : '';
        }

        if (strpos($filename, 'http') === 0) {
            return $filename;
        }

        return base_url('uploads/products/' . $productId . '/' . $filename);
    }
}