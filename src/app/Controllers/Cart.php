<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\ProductModel;

class Cart extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $userId;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        
        helper('auth');
        $this->userId = user_id(); // Récupère l'ID depuis la session
    }

    private function getCartUserId() 
    {
        // Si utilisateur connecté, on utilise son ID
        if ($this->userId) {
            return $this->userId;
        }
        
        // Sinon, gestion panier invité (Guest)
        // TODO: Implémenter logique session guest si nécessaire
        // Pour l'instant, on redirige vers login ou on bloque
        return null;
    }

    public function index()
    {
        $userId = $this->getCartUserId();
        if (!$userId) {
            // Optionnel : Rediriger vers login si le panier requiert une connexion
             return redirect()->to('auth/login')->with('error', 'Vous devez être connecté pour voir votre panier.');
        }

        // Récupération du panier actif
        $cart = $this->cartModel->getActiveCart($userId);

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
        $userId = $this->getCartUserId();
        if (!$userId) return redirect()->to('auth/login');

        $cart = $this->cartModel->getActiveCart($userId);
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');

        if ($productId && $quantity > 0) {
            // Vérification du stock via le modèle
            $productModel = new ProductModel();

            if ($productModel->hasSufficientStock($productId, $quantity)) {
                $this->cartItemModel->updateQuantity($cart->id, $productId, $quantity);
                $this->cartModel->updateTotal($cart->id);
            } else {

                set_error("Stock insuffisant pour ce produit.");
                return redirect()->back()->withInput();
            }
        }

        return redirect()->to('cart');
    }

    public function remove($productId)
    {
        $userId = $this->getCartUserId();
        if (!$userId) return redirect()->to('auth/login');

        $cart = $this->cartModel->getActiveCart($userId);
        
        $this->cartItemModel->removeItem($cart->id, $productId);
        $this->cartModel->updateTotal($cart->id);

        return redirect()->to('cart');
    }

    public function add()
    {
        $userId = $this->getCartUserId();
        if (!$userId) {
            session()->setFlashdata('error', 'Veuillez vous connecter pour ajouter des articles au panier.');
            return redirect()->to('auth/login');
        }

        $cart = $this->cartModel->getActiveCart($userId);
        $productId = $this->request->getPost('product_id');
        $quantity = (int) ($this->request->getPost('quantity') ?? 1);

        if ($productId) {
            // Vérification du stock cumulé
            $productModel = model('App\Models\ProductModel');
            
            // On regarde combien on en a déjà dans le panier
            $existingItem = $this->cartItemModel->where('cart_id', $cart->id)
                                                ->where('product_id', $productId)
                                                ->first();
            
            $currentQty = $existingItem ? $existingItem->quantity : 0;
            $newTotalQty = $currentQty + $quantity;

            if ($productModel->hasSufficientStock($productId, $newTotalQty)) {
                if ($existingItem) {
                    $this->cartItemModel->updateQuantity($cart->id, $productId, $newTotalQty);
                } else {
                    $this->cartItemModel->addItem($cart->id, $productId, $quantity);
                }
                $this->cartModel->updateTotal($cart->id);
            } else {
                set_error("Stock insuffisant (Max disponible dépassé).");
                return redirect()->back()->withInput();
            }
        }

        return redirect()->to('cart');
    }

    public function clear()
    {
        $userId = $this->getCartUserId();
        if (!$userId) return redirect()->to('auth/login');

        $cart = $this->cartModel->getActiveCart($userId);
        $this->cartModel->emptyCart($cart->id);
        
        return redirect()->to('cart');
    }
}

