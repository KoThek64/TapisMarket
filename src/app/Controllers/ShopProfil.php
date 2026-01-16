<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SellerModel;
use App\Models\ProductModel;
use \CodeIgniter\Exceptions\PageNotFoundException;

class ShopProfil extends BaseController
{
    /**
     * Public Seller Profile View
     */
    public function index($shopName)
    {
        // Decode shop name from URL (slug handling)
        $shopName = urldecode($shopName);
        
        $sellerModel = new SellerModel();
        
        // Find seller by shop name
        $seller = $sellerModel->where('shop_name', $shopName)
                              ->first();
                              
        if (!$seller) {
            throw PageNotFoundException::forPageNotFound("Vendeur introuvable : " . $shopName);
        }

        // Get seller's products
        $productModel = new ProductModel();
        // Assuming there's a method to get approved products by seller_id
        $products = $productModel->select('products.*, product_photos.file_name as image')
                                 ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                                 ->where('seller_id', $seller->user_id)
                                 ->where('product_status', STATUS_APPROVED)
                                 ->paginate(9);

        // Get Reviews (if you have review logic specific to sellers or products of seller)
        // For now, let's just show basic info
        
        $data = [
            'seller' => $seller,
            'products' => $products,
            'pager' => $productModel->pager,
        ];

        return view('pages/seller_profile', $data);
    }
}
