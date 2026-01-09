<?php

namespace App\Enums;

enum ShippingType: string
{
    case STANDARD = 'standard';
    case EXPRESS = 'express';
    case FREE = 'free';
    case INTERNATIONAL = 'international';
}
