<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN   = 'admin';
    case SELLER = 'vendeur';
    case CLIENT  = 'client';
}
