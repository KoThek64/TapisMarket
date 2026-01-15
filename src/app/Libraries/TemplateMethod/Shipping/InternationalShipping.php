<?php

namespace App\Libraries\TemplateMethod\Shipping;

class InternationalShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return INTERNATIONAL_SHIPPING_COST_DEFAULT;
    }

    public function additionalCostPerItem() : float
    { 
        return INTERNATIONAL_ADDITIONAL_COST_PER_ITEM;
    }
}
