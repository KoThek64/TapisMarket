<?php

namespace App\Libraries\Factories;

use App\Enums\ShippingType;
use App\Libraries\Strategies\Shipping\ShippingStrategy;
use App\Libraries\Strategies\Shipping\StandardShipping;
use App\Libraries\Strategies\Shipping\ExpressShipping;
use App\Libraries\Strategies\Shipping\FreeShipping;
use App\Libraries\Strategies\Shipping\InternationalShipping;
use InvalidArgumentException;

class ShippingStrategyFactory
{
    /**
     * Creates a ShippingStrategy instance based on the requested type.
     *
     * @param ShippingType $type The shipping type
     * @return ShippingStrategy
     * @throws InvalidArgumentException If the shipping type is not recognized
     */
    public static function create(ShippingType $type): ShippingStrategy
    {
        return match ($type) {
            ShippingType::STANDARD => new StandardShipping(),
            ShippingType::EXPRESS => new ExpressShipping(),
            ShippingType::FREE => new FreeShipping(),
            ShippingType::INTERNATIONAL => new InternationalShipping(),
        };
    }
}
