<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Administrateur extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'id_utilisateur' => 'integer',
    ];
}