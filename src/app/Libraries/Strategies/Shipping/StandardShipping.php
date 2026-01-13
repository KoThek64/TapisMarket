<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;

class StandardShipping implements ShippingStrategy
{
    public $shippingCostDefault = 14.99;

    public function calculate(Order $order): float
    {
        
        return $this->shippingCostDefault;
    }
}
