<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;

class Checkout extends BaseController
{
    protected $userId;
    protected $cartModel;
    protected $orderModel;
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userId = $this->getUserId();
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $cartData = $this->getCartData($this->userId);

        // Sécurité : Si pas de panier, redirection
        if (!$cartData) {
            return redirect()->to('pages/catalogue');
        }

        // Si le panier est vide
        if (empty($cartData['items'])) {
            return redirect()->to('cart');
        }

        return view('pages/checkout', $cartData);
    }

    public function process()
    {
        // On récupère à nouveau les données pour valider l'état du panier
        $cartData = $this->getCartData($this->userId);

        if (!$cartData) {
            return redirect()->to('pages/catalogue');
        }

        if (empty($cartData['items'])) {
            return redirect()->to('cart');
        }

        $rules = [
            'address'     => [
                'rules'  => 'required|min_length[5]',
                'errors' => ['required' => 'L\'adresse est requise.', 'min_length' => 'L\'adresse est trop courte.']
            ],
            'city'        => [
                'rules'  => 'required|min_length[2]',
                'errors' => ['required' => 'La ville est requise.']
            ],
            'zip'         => [
                'rules'  => 'required|numeric|min_length[4]',
                'errors' => ['numeric' => 'Le code postal doit être numérique.']
            ],
            'card_number' => [
                'rules'  => 'required|regex_match[/^[0-9\s]{16,23}$/]',
                'errors' => ['regex_match' => 'Le numéro de carte doit contenir 16 chiffres.']
            ],
            'card_expiry' => [
                'rules'  => 'required|regex_match[/^(0[1-9]|1[0-2])\/[0-9]{2}$/]',
                'errors' => ['regex_match' => 'La date d\'expiration doit être au format MM/YY (ex: 12/25).']
            ],
            'card_cvc'    => [
                'rules'  => 'required|numeric|min_length[3]|max_length[4]',
                'errors' => ['numeric' => 'Le CVC est invalide.']
            ],
        ];

        if (! $this->validate($rules)) {
            return view('pages/checkout', array_merge($cartData, [
                'validation' => $this->validator
            ]));
        }

        // Préparation des données pour le modèle
        $shippingDetails = [
            'address' => $this->request->getPost('address'),
            'city'    => $this->request->getPost('city'),
            'zip'     => $this->request->getPost('zip'),
        ];

        $orderId = $this->processOrder($this->userId, $shippingDetails);

        if (!$orderId) {
            return redirect()->back()->withInput()->with('error', 'Une erreur technique est survenue lors de la création de la commande.');
        }

        return view('pages/order_success');
    }

    /**
     * Récupère l'ID utilisateur (logique temporaire basée sur le premier client)
     */
    private function getUserId()
    {
        $customer = $this->db->table('customers')->select('user_id')->limit(1)->get()->getRow();
        return $customer ? $customer->user_id : 1;
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
            'status'      => defined('ORDER_PAID') ? ORDER_PAID : 'PAID',
            'total_ttc'   => $cart->total,
            'shipping_fees' => 0,
            'delivery_method' => 'Standard',
            'delivery_street' => $shippingDetails['address'],
            'delivery_postal_code' => $shippingDetails['zip'],
            'delivery_city' => $shippingDetails['city'],
            'delivery_country' => 'France',
        ];

        return $this->orderModel->createOrderFromCart($userId, $orderData, $cart, $items);
    }
}
