<?php

namespace App\Libraries\TemplateMethod\Shipping;

class InternationalShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return internationalShippingCostDefault;
    }

    public function additionalCostPerItem() : float
    { 
        return internationalAdditionalCostPerItem;
    }
}
