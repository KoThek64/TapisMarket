<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = User::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'email',
        'password',
        'lastname',
        'firstname',
        'role'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''

    protected $validationRules = [
        'email'        => 'required|valid_email|is_unique[users.email]',
        'lastname'     => 'required|min_length[2]',
        'firstname'    => 'required|min_length[2]',
        'password'     => 'required|min_length[8]',
        'role'         => 'in_list[ADMIN,SELLER,CUSTOMER]'
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already used.'
        ]
    ];


    // Check connection information
    public function checkConnection(string $email, string $password)
    {
        $user = $this->where('email', $email)->first();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // Get a user by email (e.g. for forgot password)
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // Get users by role
    public function getUsersByRole(string $role, int $perPage = 20)
    {
        return $this->where('role', $role)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
    }

     // recupere les utilisateurs avec pagination
    public function getAdminAllUsersPaginated(int $perPage = 10, ?string $role = null)
    {
        $builder = $this->select('users.*, sellers.status as seller_status')
            ->join('sellers', 'sellers.user_id = users.id', 'left')
            ->orderBy('users.created_at', 'DESC');

        if ($role && in_array($role, ['SELLER', 'CUSTOMER'])) {
            $builder->where('users.role', $role);
        }

        return $builder->paginate($perPage, 'users');
    }

    // Count total users
    public function countAllUsers()
    {
        return $this->countAllResults();
    }

    // Get latest registered users
    public function getLatestRegistered(int $limit = 5)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->findAll($limit);
    }
}
