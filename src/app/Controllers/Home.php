<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data['products'] = $productModel->getAllWithImage(6); 
        
        return view('accueil', $data);
    }
}