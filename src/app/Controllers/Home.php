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
        return view('FooterViews/legalNotice');
    }

    public function confidentiality()
    {
        return view('FooterViews/confidentiality');
    }

    public function deliveryReturns()
    {
        return view('FooterViews/deliveryReturns');
    }

    public function FAQ()
    {
        return view('FooterViews/faq');
    }
}