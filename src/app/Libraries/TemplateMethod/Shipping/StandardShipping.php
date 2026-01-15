<?php

namespace App\Libraries\TemplateMethod\Shipping;

class StandardShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return standardShippingCostDefault;
    }

    public function additionalCostPerItem() : float
    { 
        return standardAdditionalCostPerItem;
    }
}
