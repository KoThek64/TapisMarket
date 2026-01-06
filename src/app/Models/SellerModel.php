<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Seller;

class SellerModel extends Model
{
    protected $table            = 'sellers';
    protected $primaryKey       = 'user_id';
    protected $returnType       = Seller::class;

    protected $useAutoIncrement = false; 

    protected $useSoftDeletes   = true; 
    protected $deletedField     = 'deleted_at';
    
    protected $allowedFields    = [
        'user_id', 
        'shop_name', 
        'shop_description', 
        'siret', 
        'status', 
        'refusal_reason',
        'deleted_at'
    ];

    protected $validationRules = [
        'shop_name' => 'required|min_length[3]',
        'siret'     => 'required|exact_length[14]|is_unique[sellers.siret,user_id,{user_id}]',
        'status'    => 'in_list['.SELLER_PENDING.','.SELLER_VALIDATED.','.SELLER_REFUSED.','.SELLER_SUSPENDED.']',
    ];

    protected $validationMessages = [
        'siret' => [
            'is_unique' => 'This SIRET number is already registered on the platform.'
        ]
    ];


    // Retrieves the complete profile of a seller with user info
    public function getFullProfile(int $userId)
    {
        return $this->select('sellers.*, users.email, users.lastname, users.firstname, users.created_at')
                    ->join('users', 'users.id = sellers.user_id')
                    ->find($userId);
    }

    public function countSellersPendingValidation()
    {
        return $this->where('status', SELLER_PENDING)->countAllResults();
    }

    // Retrieves pending sellers
    public function getSellersPendingValidation(int $perPage = 20)
    {
        return $this->select('sellers.user_id, sellers.shop_name, sellers.siret, sellers.status, users.created_at as registration_date, users.email')
                    ->join('users', 'users.id = sellers.user_id') 
                    ->where('status', SELLER_PENDING)
                    ->orderBy('users.created_at', 'ASC')
                    ->paginate($perPage, 'vendors');
    }

    // Validate seller
    public function validateSeller(int $sellerId)
    {
        return $this->update($sellerId, [
            'status' => SELLER_VALIDATED,
            'refusal_reason' => null
        ]);
    }

    // Refuse a seller with a reason
    public function rejectSeller(int $sellerId, string $reason)
    {
        return $this->update($sellerId, [
            'status' => SELLER_REFUSED,
            'refusal_reason' => $reason
        ]);
    }
}