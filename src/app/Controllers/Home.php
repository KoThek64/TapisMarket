<?php

namespace App\Controllers;

use App\Models\ProduitModel;

class Home extends BaseController
{
    public function index()
    {
        $produitModel = new ProduitModel();

        $data['produits'] = $produitModel->getProduitsAvecImage(6); 
        
        return view('accueil', $data);
    }
}