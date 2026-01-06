<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Administrator extends Entity
{
    protected $datamap = [];
    protected $dates   = [];
    protected $casts   = [
        'user_id' => 'integer',
    ];
}