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
        'created_at',
        'role'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = null;

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


    // verifie la connection 
    public function checkConnection(string $email, string $password)
    {
        $user = $this->where('email', $email)->first();
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    // recuper par le mail 
    public function getByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }

    // recupere les utilisateurs par leurs roles 
    public function getUsersByRole(string $role, int $perPage = 20)
    {
        return $this->where('role', $role)
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    // recupere les utilisateurs avec pagination
    public function getAllUsersPaginated(int $perPage = 10, ?string $role = null)
    {
        $builder = $this->select('users.*, sellers.status as seller_status')
            ->join('sellers', 'sellers.user_id = users.id', 'left')
            ->orderBy('users.created_at', 'DESC');

        if ($role && in_array($role, ['SELLER', 'CUSTOMER'])) {
            $builder->where('users.role', $role);
        }

        return $builder->paginate($perPage, 'users');
    }

    //compte tous les utilisateurs
    public function countAllUsers()
    {
        return $this->countAllResults();
    }

    // recupere les derniers connecter
    public function getLatestRegistered(int $limit = 5)
    {
        return $this->select('users.*, sellers.status as seller_status')
            ->join('sellers', 'sellers.user_id = users.id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->findAll($limit);
    }
}
