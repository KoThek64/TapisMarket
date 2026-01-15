<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Customer;
use \App\Models\UserModel;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'user_id';
    protected $returnType = Customer::class;

    protected $useAutoIncrement = false;

    protected $allowedFields = ['user_id', 'phone', 'birth_date'];

    protected $validationRules = [
        'user_id' => 'required|integer|is_unique[customers.user_id]',
        'phone' => 'permit_empty|min_length[10]|max_length[20]',
        'birth_date' => 'permit_empty|valid_date',
    ];

    protected $validationMessages = [
        'phone' => [
            'min_length' => 'The phone number is too short.'
        ]
    ];

    public $lastErrors = [];

    // Retrieves the complete profile of a client with user info
    public function getByEmail(string $email)
    {
        return $this->select('customers.*, users.email, users.password, users.lastname, users.firstname, users.created_at')
            ->join('users', 'users.id = customers.user_id')
            ->where('users.email', $email)
            ->first();
    }

    // Returns the customer to display a personalized message if connected
    public function getFullProfile(int $id)
    {
        return $this->select('customers.*, users.lastname, users.firstname, users.email, users.created_at')
            ->join('users', 'users.id = customers.user_id')
            ->find($id);
    }


    // Bonus for admin dashboard: see latest registered customers
    public function getLatestRegistered(int $limit = 5)
    {
        return $this->select('customers.*, users.lastname, users.firstname, users.created_at')
            ->join('users', 'users.id = customers.user_id')
            ->orderBy('users.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    //Create a new customer
    public function createCustomer(array $userData, array $customerData = [])
    {
        $this->db->transStart();
        $this->lastErrors = [];

        $userModel = new UserModel();
        $userId = null;

        try {
            $userId = $userModel->insert($userData, true);
        } catch (\Exception $e) {
            $this->db->transRollback();
            $this->lastErrors = ['SQL Error: ' . $e->getMessage()];
            return false;
        }

        if (!$userId) {
            $this->db->transRollback();
            $this->lastErrors = $userModel->errors();
            return false;
        }

        $customerData['user_id'] = $userId;

        if (!$this->insert($customerData)) {
            $this->db->transRollback();
            $this->lastErrors = $this->errors();
            return false;
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->lastErrors = ['Transaction failed'];
            return false;
        }

        return true;
    }
}
