<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Administrator;

class AdministratorModel extends Model
{
    protected $table            = 'administrators';
    protected $primaryKey       = 'user_id'; 
    protected $returnType       = Administrator::class;

    protected $useAutoIncrement = false;

    protected $allowedFields    = ['user_id'];

    protected $validationRules  = [
        'user_id' => 'required|integer|is_unique[administrators.user_id]',
    ];
    
    protected $validationMessages = [
        'user_id' => [
            'is_unique' => 'This user is already an administrator.'
        ]
    ];

    // Get full admin profile by ID
    public function getAdminProfile(int $id)
    {
        return $this->select('administrators.*, users.lastname, users.firstname, users.email, users.created_at')
                    ->join('users', 'users.id = administrators.user_id')
                    ->find($id);
    }
}