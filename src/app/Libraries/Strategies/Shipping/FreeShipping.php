<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;

class FreeShipping implements ShippingStrategy
{
    public $shippingCostDefault = 0.00;
    
    public function calculate(Order $order): float
    {
        return $this->shippingCostDefault;
    }
}
