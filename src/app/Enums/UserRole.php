<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN   = 'admin';
    case VENDEUR = 'vendeur';
    case CLIENT  = 'client';
}