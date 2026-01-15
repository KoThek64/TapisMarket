<?php

namespace App\Libraries\TemplateMethod\Shipping;;

class ExpressShipping extends ShippingTemplateMethod
{
    public function shippingCostDefault() : float 
    { 
        return EXPRESS_SHIPPING_COST_DEFAULT;
    }

    public function additionalCostPerItem() : float
    { 
        return EXPRESS_ADDITIONAL_COST_PER_ITEM;
    }
}
