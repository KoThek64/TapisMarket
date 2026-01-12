<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;

class Cart extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $userId;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();

        // Récupération ou création d'un utilisateur par défaut pour le développement
        $db = \Config\Database::connect();
        $customer = $db->table('customers')->select('user_id')->limit(1)->get()->getRow();

        if ($customer) {
            $this->userId = $customer->user_id;
        } else {
            // Création d'un client de test si la base est vide
            $db->table('users')->insert([
                'email'      => 'test_dev_cart@example.com',
                'password'   => password_hash('123456', PASSWORD_DEFAULT),
                'lastname'   => 'Dev',
                'firstname'  => 'Test',
                'role'       => 'CUSTOMER',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $newUserId = $db->insertID();
            
            $db->table('customers')->insert([
                'user_id' => $newUserId,
                'phone'   => '0600000000'
            ]);
            
            $this->userId = $newUserId;
        }
    }

    public function index()
    {
        // Récupération du panier actif
        $cart = $this->cartModel->getActiveCart($this->userId);

        // Recalcul du total pour assurer la cohérence des données
        $this->cartModel->updateTotal($cart->id);
        $cart = $this->cartModel->find($cart->id);

        return view('pages/cart', [
            'cart'       => $cart,
            'items'      => $this->cartModel->getCartItems($cart->id),
            'totalItems' => $this->cartItemModel->getTotalItemsCount($cart->id)
        ]);
    }

    public function update()
    {
        $cart = $this->cartModel->getActiveCart($this->userId);
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');

        if ($productId && $quantity > 0) {
            // Vérification du stock via le modèle
            $productModel = model('App\Models\ProductModel');

            if ($productModel->hasSufficientStock($productId, $quantity)) {
                $this->cartItemModel->updateQuantity($cart->id, $productId, $quantity);
                $this->cartModel->updateTotal($cart->id);
            } else {
                return redirect()->to('cart')->with('error', 'Stock insuffisant pour ce produit.');
            }
        }

        return redirect()->to('cart');
    }

    public function remove($productId)
    {
        $cart = $this->cartModel->getActiveCart($this->userId);
        
        $this->cartItemModel->removeItem($cart->id, $productId);
        $this->cartModel->updateTotal($cart->id);

        return redirect()->to('cart');
    }

    public function add()
    {
        $cart = $this->cartModel->getActiveCart($this->userId);
        $productId = $this->request->getPost('product_id');
        $quantity = (int) ($this->request->getPost('quantity') ?? 1);

        if ($productId) {
            // Vérification du stock cumulé
            $productModel = model('App\Models\ProductModel');
            
            // On regarde combien on en a déjà dans le panier
            $existingItem = $this->cartItemModel->where('cart_id', $cart->id)
                                                ->where('product_id', $productId)
                                                ->first();
            
            // Si le produit est déjà dans le panier, on empêche l'ajout
            if ($existingItem) {
                return redirect()->back()->with('error', 'Ce produit est déjà dans votre panier.');
            }

            if ($productModel->hasSufficientStock($productId, $quantity)) {
                $this->cartItemModel->addItem($cart->id, $productId, $quantity);
                $this->cartModel->updateTotal($cart->id);
            } else {
                 return redirect()->back()->with('error', 'Stock insuffisant.');
            }
        }

        return redirect()->to('cart');
    }
}

