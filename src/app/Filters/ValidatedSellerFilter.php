<?php

namespace App\Filters;

use App\Enums\UserRole;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

helper('auth_helper');

class ValidatedSellerFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        $role = user_role();

        if ($role != UserRole::SELLER) {
            return redirect()->to('/auth/login')->with('error', 'Vous devez être un vendeur.');
        }

        $userData = user_data();
        if ($userData->status != SELLER_VALIDATED) {
            return redirect()->to('seller-validation-error');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
