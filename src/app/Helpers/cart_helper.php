<?php

use App\Models\CartModel;
use App\Models\CartItemModel;


function count_cart_items(): int
{
    helper('auth');

    $userId = user_id();

    if ($userId) {
        $cartModel = new CartModel();

        $cart = $cartModel->getActiveCart($userId);

        if (!$cart)
            return 0;

        $cartItemModel = new CartItemModel();
        return $cartItemModel->getTotalItemsCount($cart->id);

    } else {
        $session = session();
        $guestCart = $session->get('guest_cart');

        if (empty($guestCart) || !is_array($guestCart)) {
            return 0;
        }

        return array_sum($guestCart);
    }
}
