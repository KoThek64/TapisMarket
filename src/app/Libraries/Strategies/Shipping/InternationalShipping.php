<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;
use App\Models\OrderModel;

class InternationalShipping implements ShippingStrategy
{
    public function calculate(Order $order): float
    {
        // Higher shipping costs for international
        $shippingCost = 39.99;

        $itemCount = 0;
        
        if ($order->id) {
            $orderModel = new OrderModel();
            $itemCount = $orderModel->getItemCount($order->id);
        }

        if ($itemCount > 1) {
            $shippingCost += ($itemCount - 1) * 2.50;
        }

        return $shippingCost;
    }
}
