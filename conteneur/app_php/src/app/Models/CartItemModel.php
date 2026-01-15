<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\CartItem;

class CartItemModel extends Model
{
    protected $table = 'cart_items';
    protected $primaryKey = 'item_id';
    protected $useAutoIncrement = true;
    protected $returnType = CartItem::class;

    protected $allowedFields = ['item_id', 'cart_id', 'product_id', 'quantity'];

    protected $validationRules = [
        'quantity' => 'required|integer|greater_than[0]',
    ];

    public function addItem($cartId, $productId, $quantity)
    {
        $item = $this->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            return $this->save($item);
        } else {
            return $this->insert([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }
    }

    // Sets exact quantity
    public function updateQuantity($cartId, $productId, $newQuantity)
    {
        if ($newQuantity <= 0) {
            return $this->removeItem($cartId, $productId);
        }

        return $this->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->set(['quantity' => $newQuantity])
            ->update();
    }

    // Removes a product from the cart
    public function removeItem($cartId, $productId)
    {
        return $this->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->delete();
    }

    // Returns the total number of items for the header
    public function getTotalItemsCount($cartId): int
    {
        $result = $this->selectSum('quantity')
            ->where('cart_id', $cartId)
            ->first();

        return (int) ($result->quantity ?? 0);
    }
}
