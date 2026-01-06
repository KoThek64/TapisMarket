<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Enums\UserRole;

class AuthFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {

        $role = user_role();

        if ($role === null) {
            return redirect()->to('/login')->with('error', 'Vous devez être connecté.');
        }

        if (!empty($arguments)) {
            // On vérifie si le rôle de l'utilisateur est dans la liste des arguments autorisés
            if (!in_array($role->value, $arguments)) {
                return redirect()->to('/')->with('error', 'Accès non autorisé.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
