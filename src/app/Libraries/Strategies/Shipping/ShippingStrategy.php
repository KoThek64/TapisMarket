<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;

interface ShippingStrategy
{
    /**
     * Calculates the shipping fees for a given order.
     *
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float;
    
}
