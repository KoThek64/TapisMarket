<?php

namespace App\Libraries\TemplateMethod\Shipping;

class StandardShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault(): float
    {
        return STANDARD_SHIPPING_COST_DEFAULT;
    }

    public function additionalCostPerItem(): float
    {
        return STANDARD_ADDITIONAL_COST_PER_ITEM;
    }
}
