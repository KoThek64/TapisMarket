<?php

namespace App\Libraries\TemplateMethod\Shipping;

class FreeShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return FREE_SHIPPING_COST_DEFAULT;
    }

    public function additionalCostPerItem() : float
    { 
        return FREE_ADDITIONAL_COST_PER_ITEM;
    }
}
