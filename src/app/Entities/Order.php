<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\DateTrait;
use App\Traits\PriceTrait;

class Order extends Entity
{
    use DateTrait;
    use PriceTrait;

    protected $dates = ['order_date'];

    protected $casts = [
        'id'          => 'integer',
        'customer_id' => 'integer',
        'total_ttc'   => 'float',
        'shipping_fees' => 'float',
    ];

    // Use consistent name with Product
    public function getFormattedPrice(): string
    {
        return $this->formatPrice($this->total_ttc);
    }

    // Check if order is completed
    public function isCompleted(): bool
    {
        return in_array($this->status, ['DELIVERED', 'CANCELLED']);
    }

    // Format order date
    public function getFormattedDate(): string
    {
        return $this->formatDate($this->order_date);
    }

    public function getFullDeliveryAddress(): string
    {
        return $this->delivery_street . ', ' . 
               $this->delivery_postal_code . ' ' . 
               $this->delivery_city . ' (' . 
               strtoupper($this->delivery_country) . ')';
    }
}