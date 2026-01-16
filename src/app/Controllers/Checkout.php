<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\AddressModel;

class Checkout extends BaseController
{
    protected $userId;
    protected $cartModel;
    protected $orderModel;
    protected $db;
    protected $addressModel;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->db = \Config\Database::connect();
        $this->userId = user_id();
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
        $this->addressModel = new AddressModel();
    }

    public function index()
    {
        if (!$this->userId) {
            return redirect()->to('auth/login')->with('error', 'Veuillez vous connecter pour valider votre commande.');
        }

        $cartData = $this->getCartData($this->userId);

        if (!$cartData) {
            return redirect()->to('pages/catalogue');
        }

        if (empty($cartData['items'])) {
            return redirect()->to('cart');
        }

        // Récupération des adresses
        $cartData['addresses'] = $this->addressModel->where('user_id', $this->userId)->findAll();

        return view('pages/checkout', $cartData);
    }

    public function process()
    {
        $cartData = $this->getCartData($this->userId);

        if (!$cartData) {
            return redirect()->to('pages/catalogue');
        }

        if (empty($cartData['items'])) {
            return redirect()->to('cart');
        }

        $rules = [
            'address' => [
                'rules' => 'required|min_length[5]',
                'errors' => ['required' => 'L\'adresse est requise.', 'min_length' => 'L\'adresse est trop courte.']
            ],
            'city' => [
                'rules' => 'required|min_length[2]',
                'errors' => ['required' => 'La ville est requise.']
            ],
            'zip' => [
                'rules' => 'required|numeric|min_length[4]',
                'errors' => ['numeric' => 'Le code postal doit être numérique.']
            ],
            'card_number' => [
                'rules' => 'required|regex_match[/^[0-9\s]{16,23}$/]',
                'errors' => ['regex_match' => 'Le numéro de carte doit contenir 16 chiffres.']
            ],
            'card_expiry' => [
                'rules' => 'required|regex_match[/^(0[1-9]|1[0-2])\/[0-9]{2}$/]',
                'errors' => ['regex_match' => 'La date d\'expiration doit être au format MM/YY (ex: 12/25).']
            ],
            'card_cvc' => [
                'rules' => 'required|numeric|min_length[3]|max_length[4]',
                'errors' => ['numeric' => 'Le CVC est invalide.']
            ],
            'shipping_method' => [
                'rules' => 'required|in_list[standard,express,international,free]',
                'errors' => ['required' => 'Veuillez choisir un mode de livraison.', 'in_list' => 'Mode de livraison invalide.']
            ],
        ];

        if (!$this->validate($rules)) {
            // Récupération des adresses pour réaffichage
            $cartData['addresses'] = $this->addressModel->where('user_id', $this->userId)->findAll();

            return view('pages/checkout', array_merge($cartData, [
                'validation' => $this->validator
            ]));
        }

        // Préparation des données pour le modèle
        $shippingDetails = [
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'zip' => $this->request->getPost('zip'),
            'method' => $this->request->getPost('shipping_method'),
        ];

        // Sauvegarde de la nouvelle adresse si demandé
        if ($this->request->getPost('save_address')) {

            $rawAddress = $shippingDetails['address'];
            $number = null;
            $street = $rawAddress;

            // Tentative d'extraction simpliste du numéro
            if (preg_match('/^(\d+)\s+(.*)$/', $rawAddress, $matches)) {
                $number = $matches[1];
                $street = $matches[2];
            }

            $this->addressModel->insert([
                'user_id' => $this->userId,
                'number' => $number,
                'street' => $street,
                'postal_code' => $shippingDetails['zip'],
                'city' => $shippingDetails['city'],
                'country' => 'France'
            ]);
        }

        $orderId = $this->processOrder($this->userId, $shippingDetails);

        if (!$orderId) {
            return redirect()->back()->withInput()->with('error', 'Une erreur technique est survenue lors de la création de la commande.');
        }

        return view('pages/order_success');
    }

    /**
     * Récupère le panier actif et ses articles pour un utilisateur
     */
    private function getCartData($userId)
    {
        $cart = $this->cartModel->getActiveCart($userId);

        if (!$cart) {
            return null;
        }

        $items = $this->cartModel->getCartItems($cart->id);

        return [
            'cart' => $cart,
            'items' => $items
        ];
    }

    /**
     * Traite la commande à partir du panier actif
     */
    private function processOrder($userId, $shippingDetails)
    {
        $cartData = $this->getCartData($userId);

        if (!$cartData || empty($cartData['items'])) {
            return false;
        }

        $cart = $cartData['cart'];
        $items = $cartData['items'];

        $orderData = [
            'customer_id' => $userId,
            'status' => defined('ORDER_PAID') ? ORDER_PAID : 'PAID',
            'total_ttc' => $cart->total,
            'shipping_fees' => 0, // Will be calculated in model
            'delivery_method' => ucfirst($shippingDetails['method'] ?? 'Standard'),
            'delivery_street' => $shippingDetails['address'],
            'delivery_postal_code' => $shippingDetails['zip'],
            'delivery_city' => $shippingDetails['city'],
            'delivery_country' => 'France',
        ];

        return $this->orderModel->createOrderFromCart($userId, $orderData, $cart, $items);
    }
}
