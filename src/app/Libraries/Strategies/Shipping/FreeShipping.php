<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;

class FreeShipping implements ShippingStrategy
{
    public function calculate(Order $order): float
    {
        return 0.00;
    }
}
