<?php

namespace App\Libraries\TemplateMethod\Shipping;

use App\Entities\Order;
use App\Enums\ShippingType;
use InvalidArgumentException;
use App\Models\OrderModel;

abstract class ShippingTemplateMethod
{
    /**
     * Calculates the shipping fees for a given order.
     *
     * @param Order $order
     * @return float
     */
    public function calculate(Order $order): float
    {
        $itemCount = 0;
        $totalCost = $this->shippingCostDefault();
        
        if ($order->id) {
            $orderModel = new OrderModel();
            $itemCount = $orderModel->getItemCount($order->id);
        } elseif (!empty($order->items)) {
             $itemCount = count($order->items);
        }

        if ($itemCount > 1) {
            $totalCost += ($itemCount - 1) * $this->additionalCostPerItem();
        }

        return $totalCost;
    }

    abstract public function shippingCostDefault() : float;

    abstract public function additionalCostPerItem() : float;

    /**
     * Creates a ShippingStrategy instance based on the requested type.
     *
     * @param ShippingType $type The shipping type
     * @return ShippingStrategy
     * @throws InvalidArgumentException If the shipping type is not recognized
     */
    public static function create(ShippingType $type): ShippingTemplateMethod
    {
        return match ($type) {
            ShippingType::STANDARD => new StandardShipping(),
            ShippingType::EXPRESS => new ExpressShipping(),
            ShippingType::FREE => new FreeShipping(),
            ShippingType::INTERNATIONAL => new InternationalShipping(),
        };
    }
}
