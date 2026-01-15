<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Address;

class AddressModel extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    protected $returnType = Address::class;

    protected $allowedFields = [
        'user_id',
        'number',
        'street',
        'postal_code',
        'city',
        'country',
        'contact_phone'
    ];

    protected $validationRules = [
        'user_id' => 'required|integer',
        'number' => 'permit_empty|max_length[10]',
        'street' => 'required|min_length[3]|max_length[255]',
        'postal_code' => 'required|min_length[4]|max_length[10]',
        'city' => 'required|min_length[2]|max_length[100]',
        'country' => 'required|min_length[2]|max_length[100]',
        'contact_phone' => 'permit_empty|min_length[10]|max_length[20]',
    ];

    protected $validationMessages = [
        'contact_phone' => [
            'min_length' => 'The phone number is too short.'
        ]
    ];

    // Retrieves addresses of a user
    public function getUserAddresses(int $userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    // Deletes an address of a user
    public function deleteAddress(int $addressId, int $userId)
    {
        return $this->where('id', $addressId)
            ->where('user_id', $userId)
            ->delete();
    }
}
