<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data['products'] = $productModel->getAllWithImage(6); 
        
        return view('home', $data);
    }

    public function legalNotice()
    {
        return view('pages/legalNotice');
    }

    public function confidentiality()
    {
        
        return view('pages/confidentiality');
    }

    public function deliveryReturns()
    {
        return view('pages/deliveryReturns');
    }

    public function FAQ()
    {
        return view('pages/faq');
    }

    public function sellerValidationError() {
        return view('pages/seller-validation-error');
    }
}