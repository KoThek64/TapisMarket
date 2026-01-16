<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\CartItemModel;
use App\Models\ProductModel;
use App\Enums\UserRole;
use App\Entities\CartItem;

class Cart extends BaseController
{
    protected $cartModel;
    protected $cartItemModel;
    protected $userId;
    protected $productModel;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->productModel = new ProductModel();

        $this->userId = user_id();
    }

    private function getCartUserId()
    {
        // Deprecated: logic handled inline
        return $this->userId;
    }

    public function index()
    {
        $role = user_role();
        if ($role === UserRole::ADMIN || $role === UserRole::SELLER) {
            set_error("Vous devez être un client pour avoir un panier.");
            return redirect()->back()->withInput();
        }
        if ($this->userId) {
            // Récupération du panier actif pour utilisateur connecté
            $cart = $this->cartModel->getActiveCart($this->userId);

            // Recalcul du total pour assurer la cohérence des données
            $this->cartModel->updateTotal($cart->id);
            $cart = $this->cartModel->find($cart->id);
            $items = $this->cartModel->getCartItems($cart->id);
            $totalItems = $this->cartItemModel->getTotalItemsCount($cart->id);
        } else {
            // Gestion du panier invité via Session
            $guestCart = session()->get('guest_cart') ?? [];
            $items = [];
            $total = 0;
            $totalItems = 0;

            if (!empty($guestCart)) {
                $ids = array_keys($guestCart);

                // On récupère les infos produits
                $dbItems = $this->cartModel->builder('products')
                    ->select('products.id as product_id, products.price, products.title, products.alias, products.stock_available, products.short_description, categories.name as category_name, product_photos.file_name as image')
                    ->join('categories', 'categories.id = products.category_id', 'left')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->whereIn('products.id', $ids)
                    ->get()
                    ->getResultArray();

                foreach ($dbItems as $row) {
                    $pid = $row['product_id'];
                    $qty = $guestCart[$pid];

                    $row['quantity'] = $qty;
                    // Hydrate CartItem entity
                    $itemEntity = new CartItem($row);
                    $items[] = $itemEntity;

                    $total += $row['price'] * $qty;
                    $totalItems += $qty;
                }
            }

            // Fake Cart entity
            $cart = new \App\Entities\Cart();
            $cart->total = $total;
            $cart->id = 0;
        }

        return view('pages/cart', [
            'cart' => $cart,
            'items' => $items,
            'totalItems' => $totalItems
        ]);
    }

    public function update()
    {
        $productId = $this->request->getPost('product_id');
        $quantity = (int) $this->request->getPost('quantity');

        if (!$productId)
            return redirect()->back();

        // Si qté <= 0 on considère que c'est suppression
        if ($quantity <= 0) {
            return $this->remove($productId);
        }

        $productModel = new ProductModel();

        if ($this->userId) {
            $cart = $this->cartModel->getActiveCart($this->userId);

            // Vérification du stock via le modèle
            if ($productModel->hasSufficientStock($productId, $quantity)) {
                $this->cartItemModel->updateQuantity($cart->id, $productId, $quantity);
                $this->cartModel->updateTotal($cart->id);
            } else {
                set_error("Stock insuffisant pour ce produit.");
                return redirect()->back()->withInput();
            }
        } else {
            // Guest Update
            if ($productModel->hasSufficientStock($productId, $quantity)) {
                $session = session();
                $cart = $session->get('guest_cart') ?? [];
                $cart[$productId] = $quantity;
                $session->set('guest_cart', $cart);
            } else {
                set_error("Stock insuffisant pour ce produit.");
                return redirect()->back()->withInput();
            }
        }

        return redirect()->to('cart');
    }

    public function remove($productId)
    {
        if ($this->userId) {
            $cart = $this->cartModel->getActiveCart($this->userId);

            $this->cartItemModel->removeItem($cart->id, $productId);
            $this->cartModel->updateTotal($cart->id);
        } else {
            $session = session();
            $cart = $session->get('guest_cart') ?? [];
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                $session->set('guest_cart', $cart);
            }
        }

        return redirect()->to('cart');
    }

    public function add()
    {
        $role = user_role();
        if ($role === UserRole::ADMIN || $role === UserRole::SELLER) {
            set_error("Action non autorisée pour ce compte.");
            return redirect()->back()->withInput();
        }

        $productId = $this->request->getPost('product_id');
        $quantity = (int) ($this->request->getPost('quantity') ?? 1);

        if (!$productId)
            return redirect()->back();


        if ($this->userId) {
            $cart = $this->cartModel->getActiveCart($this->userId);

            // On regarde combien on en a déjà dans le panier
            $existingItem = $this->cartItemModel->where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->first();

            $currentQty = $existingItem ? $existingItem->quantity : 0;
            $newTotalQty = $currentQty + $quantity;

            if ($this->productModel->hasSufficientStock($productId, $newTotalQty)) {
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
        } else {
            // Guest Add
            $session = session();
            $cart = $session->get('guest_cart') ?? [];

            $currentQty = $cart[$productId] ?? 0;
            $newTotalQty = $currentQty + $quantity;

            if ($this->productModel->hasSufficientStock($productId, $newTotalQty)) {
                $cart[$productId] = $newTotalQty;
                $session->set('guest_cart', $cart);
            } else {
                set_error("Stock insuffisant (Max disponible dépassé).");
                return redirect()->back()->withInput();
            }
        }

        return redirect()->to('cart');
    }

    public function clear()
    {
        if ($this->userId) {
            $cart = $this->cartModel->getActiveCart($this->userId);
            $this->cartModel->emptyCart($cart->id);
        } else {
            session()->remove('guest_cart');
        }

        return redirect()->to('cart');
    }
}

