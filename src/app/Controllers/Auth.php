<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\BaseController;
use App\Enums\UserRole;
use App\Models\UserModel;
use App\Models\SellerModel;
use App\Models\AdministratorModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $sellerModel;
    protected $administratorModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->userModel          = new UserModel();
        $this->sellerModel        = new SellerModel();
        $this->administratorModel = new AdministratorModel();
    }

    public function login()
    {
        return view('auth/login', [
            'disable_error_alert' => true
        ]);
    }

    public function attemptLogin()
    {

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $roleRaw  = $this->request->getPost('role');

        $role = UserRole::tryFrom($roleRaw);

        if (!$role) {
            set_error("Le type de compte sélectionné est invalide.");
            return redirect()->back()->withInput();
        }

        $user = null;
        switch ($role) {
            case UserRole::CLIENT:
                $user = $this->userModel->getByEmail($email);
                break;
            case UserRole::SELLER:
                $user = $this->sellerModel->getByEmail($email);
                break;
            case UserRole::ADMIN:
                $user = $this->administratorModel->getByEmail($email);
                break;
        }

        if ($user && password_verify($password, $user->password)) {
            
            // create session
            $id = $role == UserRole::CLIENT ? $user->id : $user->user_id;
            login_user($id, $role);
            set_success("Connexion réussie. Bienvenue " . ($user->firstname ?? ''));
            
            return redirect()->to('/');
        }

        set_error("Email ou mot de passe incorrect pour un compte " . $role->value);
        return redirect()->back()->withInput();
    }

    public function register()
    {
        // TODO
    }

    public function attemptRegister()
    {
        // TODO
    }

    public function logout()
    {
        logout_user();
    }
}
