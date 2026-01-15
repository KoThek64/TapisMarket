<?php

namespace App\Libraries\TemplateMethod\Shipping;

class FreeShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return freeShippingCostDefault;
    }

    public function additionalCostPerItem() : float
    { 
        return freeAdditionalCostPerItem;
    }
}
