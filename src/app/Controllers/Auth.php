<?php

namespace App\Controllers;

use App\Models\CustomerModel;
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
    protected $customerModel;
    protected $sellerModel;
    protected $administratorModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->customerModel      = new CustomerModel();
        $this->sellerModel        = new SellerModel();
        $this->administratorModel = new AdministratorModel();
    }

    public function login()
    {
        return view('auth/login', [
            'custom_error_alert' => true
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
                $user = $this->customerModel->getByEmail($email);
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
            login_user($user->user_id, $role);
            
            // Merge guest cart
            $this->mergeGuestCart($user->user_id);

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
        return redirect()->to('/auth/login')->with('success', 'Vous avez été déconnecté.');
    }

    private function mergeGuestCart(int $userId)
    {
        $session = session();
        $guestCart = $session->get('guest_cart');

        if (!empty($guestCart)) {
            $cartModel = new \App\Models\CartModel();
            $cartItemModel = new \App\Models\CartItemModel();
            $productModel = new \App\Models\ProductModel();

            $cart = $cartModel->getActiveCart($userId);

            foreach ($guestCart as $productId => $quantity) {
                // Check if item exists in user cart
                $existingItem = $cartItemModel->where('cart_id', $cart->id)
                                              ->where('product_id', $productId)
                                              ->first();
                                              
                $currentQty = $existingItem ? $existingItem->quantity : 0;
                $newTotalQty = $currentQty + $quantity;

                // Validate merged stock
                if ($productModel->hasSufficientStock($productId, $newTotalQty)) {
                    if ($existingItem) {
                         $cartItemModel->updateQuantity($cart->id, $productId, $newTotalQty);
                    } else {
                         $cartItemModel->addItem($cart->id, $productId, $quantity);
                    }
                } 
            }
            
            $cartModel->updateTotal($cart->id);
            $session->remove('guest_cart');
        }
    }
}
