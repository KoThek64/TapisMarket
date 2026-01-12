<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Cart;
use CodeIgniter\I18n\Time;

class CartModel extends Model
{
    protected $table            = 'carts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = Cart::class;

    protected $allowedFields    = [
        'customer_id', 
        'created_at',
        'updated_at', 
        'total'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at'; 

    // Get or create active cart for customer
    public function getActiveCart(int $customerId)
    {
        $cart = $this->where('customer_id', $customerId)->first();

        if (!$cart) {
            $this->insert([
                'customer_id' => $customerId,
                'total' => 0,
                'created_at' => Time::now()->toDateTimeString()
            ]);
            return $this->find($this->getInsertID());
        }
        return $cart;
    }

    // Get cart items
    public function getCartItems($cartId)
    {
        return $this->db->table('cart_items')
                        ->select('cart_items.*, products.price, products.title, products.alias, products.stock_available, products.short_description, categories.name as category_name, product_photos.file_name as image')
                        ->join('products', 'products.id = cart_items.product_id')
                        ->join('categories', 'categories.id = products.category_id', 'left')
                        ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                        ->where('cart_items.cart_id', $cartId)
                        ->get()
                        ->getResult(\App\Entities\CartItem::class); 
    }
   
    // Recalculate total
    public function updateTotal(int $cartId)
    {
        $result = $this->db->table('cart_items')
                             ->select('SUM(products.price * cart_items.quantity) as calculated_total')
                             ->join('products', 'products.id = cart_items.product_id')
                             ->where('cart_items.cart_id', $cartId)
                             ->get()
                             ->getRow();

        $newTotal = $result->calculated_total ?? 0;

        $this->update($cartId, ['total' => $newTotal]);
    }


    // Empty cart
    public function emptyCart(int $cartId)
    {
        $this->db->table('cart_items')->where('cart_id', $cartId)->delete();
        
        return $this->update($cartId, [
            'total' => 0,
            'updated_at' => Time::now()->toDateTimeString()
        ]);
    }

     public function deleteOldCarts(int $days = 30)
    {
        $limitDate = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        return $this->where('updated_at <', $limitDate)
                    ->delete();
    }
}