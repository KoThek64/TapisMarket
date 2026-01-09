<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;

class StandardShipping implements ShippingStrategy
{
    public function calculate(Order $order): float
    {
        
        return 14.99;
    }
}
