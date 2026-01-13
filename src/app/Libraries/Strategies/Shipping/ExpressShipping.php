<?php

namespace App\Libraries\Strategies\Shipping;

use App\Entities\Order;
use App\Models\OrderModel;

class ExpressShipping implements ShippingStrategy
{
    // More expensive express delivery
    public $shippingCostDefault = 24.99;

    public $additionalCostPerItem = 2.00;

    public function calculate(Order $order): float
    {
        $itemCount = 0;
        $totalCost = $this->shippingCostDefault;
        
        if ($order->id) {
            $orderModel = new OrderModel();
            $itemCount = $orderModel->getItemCount($order->id);
        } elseif (!empty($order->items)) {
             $itemCount = count($order->items);
        }

        if ($itemCount > 1) {
            $totalCost += ($itemCount - 1) * $this->additionalCostPerItem;
        }

        return $totalCost;
    }
}
