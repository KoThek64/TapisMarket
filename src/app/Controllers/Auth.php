<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\BaseController;
use App\Enums\UserRole;
use App\Models\SellerModel;
use App\Models\AdministratorModel;
use App\Models\CartItemModel;
use App\Models\CartModel;
use App\Models\ProductModel;


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
        if (session()->has('user')) {
            return redirect()->to('/');
        }
        return view('auth/register', [
            'custom_error_alert' => true
        ]);
    }

    public function attemptRegister()
    {
        // Validation des champs non liés au modèle directement ou vérifications supplémentaires
        $rules = [
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            set_error("La confirmation de mot de passe n'est pas la même que le mot de passe.");
            return redirect()->back()->withInput();
        }

        $role = $this->request->getPost('role');
        $roleValue = ($role === 'vendeur') ? 'SELLER' : 'CUSTOMER';
        $data = [
            'lastname'  => $this->request->getPost('lastname'),
            'firstname' => $this->request->getPost('firstname'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $roleValue
        ];

        if ($roleValue === 'SELLER') {
            $sellerData = [
                'shop_name' => $this->request->getPost('shop_name'),
                'shop_description' => $this->request->getPost('shop_description'),
                'siret' => $this->request->getPost('siret'),
            ];

            if (!$this->sellerModel->validate($sellerData)) {
                set_error("Le nom de la boutique ou le numéro de Siret est incorrect ou incomplet");
                return redirect()->back()->withInput();
            }

            if ($this->sellerModel->createSeller($data, $sellerData)) {
                set_success("Inscription réussie ! Connectez-vous.");
                return redirect()->to('auth/login');
            } else {
                $errors = $this->sellerModel->lastErrors;
                if (empty($errors)) $errors = $this->sellerModel->errors();
                
                // Si aucune erreur précise n'est trouvée
                if (empty($errors)) $errors = ["Erreur inconnue lors de la création du vendeur. Vérifiez les logs."];

                set_error(implode('<br>', $errors));
                return redirect()->to('auth/register')->withInput();
            }
        } else {
            $customerData = [
                'phone' => $this->request->getPost('phone')
            ];

            if ($this->customerModel->createCustomer($data, $customerData)) {
                set_success("Inscription réussie ! Connectez-vous.");
                return redirect()->to('auth/login');
            } else {
                $errors = $this->customerModel->lastErrors;
                if (empty($errors)) $errors = $this->customerModel->errors();

                // Si aucune erreur précise n'est trouvée
                if (empty($errors)) {
                    $errors = ["Erreur inconnue lors de la création du client " . (empty($errors) ? "(Aucune erreur retournée)" : "")];
                    exit;
                }

                set_error(implode('<br>', $errors));
                return redirect()->to('auth/register')->withInput();
            }
        }
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
