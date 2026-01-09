<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;
use App\Models\OrderModel;

class ExpressShipping implements ShippingStrategy
{
    public function calculate(Order $order): float
    {
        // More expensive express delivery
        $shippingCost = 24.99;

        $itemCount = 0;
        
        if ($order->id) {
            $orderModel = new OrderModel();
            $itemCount = $orderModel->getItemCount($order->id);
        }

        if ($itemCount > 1) {
            $shippingCost += ($itemCount - 1) * 2.0;
        }

        return $shippingCost;
    }
}
