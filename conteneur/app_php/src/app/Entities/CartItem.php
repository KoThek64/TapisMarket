<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\PriceTrait;
use App\Traits\ImageTrait;

class CartItem extends Entity
{
    use PriceTrait;
    use ImageTrait;

    protected $casts = [
        'item_id' => 'integer',
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
    ];

    // Calculate subtotal
    public function getSubtotal(): float
    {
        $price = $this->attributes['price'] ?? 0.00;
        $qty = $this->quantity ?? 0;

        return $price * $qty;
    }

    // Calculate subtotal
    public function getPrice(): float
    {
        $price = $this->attributes['price'] ?? 0.00;

        return $price;
    }

    // Formatted display
    public function getFormattedSubtotal(): string
    {
        return $this->formatPrice($this->getSubtotal());
    }

    //
    public function getFormattedUnitPrice(): string
    {
        return $this->formatPrice($this->attributes['price'] ?? 0);
    }

    // Returns product name
    public function getProductName(): string
    {
        return $this->attributes['title'] ?? 'Product #' . $this->product_id;
    }

    // For image display
    public function getProductImage(): string
    {
        $img = $this->attributes['image'] ?? $this->attributes['file_name'] ?? null;

        return $this->getUrlImage($img);
    }

    // To click on the product in the cart
    public function getProductLink(): string
    {
        $alias = $this->attributes['alias'] ?? $this->product_id;
        return base_url('product/' . $alias);
    }
}
