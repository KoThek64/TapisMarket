<?php

namespace App\Libraries\TemplateMethod\Shipping;;

class ExpressShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return expressShippingCostDefault;
    }

    public function additionalCostPerItem() : float
    { 
        return expressAdditionalCostPerItem;
    }
}
