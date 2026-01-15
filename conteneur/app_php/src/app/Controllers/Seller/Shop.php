<?php

namespace App\Controllers\Seller;

class Shop extends SellerBaseController
{
    // Affiche le profil du magasin du vendeur
    public function index()
    {
        $userId = $this->getSellerId();
        $shop = $this->sellerModel->getFullProfile($userId);

        $data = array_merge($this->sellerData, [
            'shop' => $shop,
            'title' => 'Mon Magasin',
            'subtitle' => 'Gérez les informations de votre boutique'
        ]);

        return view('pages/seller/shop/index', $data);
    }

    // Affiche le formulaire d'édition du profil du magasin
    public function edit()
    {
        $userId = $this->getSellerId();
        $shop = $this->sellerModel->getFullProfile($userId);

        $data = array_merge($this->sellerData, [
            'shop' => $shop,
            'title' => 'Modifier mon magasin',
            'subtitle' => 'Mettez à jour vos informations'
        ]);

        return view('pages/seller/shop/edit', $data);
    }

    // Met à jour le profil du magasin du vendeur
    public function update()
    {
        $userId = $this->getSellerId();
        $shop = $this->sellerModel->getFullProfile($userId);
        if (!$shop) {
            return redirect()->to('seller/shop')->with('error', 'Boutique introuvable.');
        }

        // On récupère uniquement les champs autorisés
        $input = $this->request->getPost();
        $data = [
            'shop_name' => $input['shop_name'] ?? $shop->shop_name,
            'shop_description' => $input['shop_description'] ?? $shop->shop_description,
            'siret' => $shop->siret,
        ];

        //On ajoute la règle de validation pour le SIRET
        $this->sellerModel->setValidationRule('siret', 'required|exact_length[14]');

        $watchedFields = ['shop_name', 'shop_description'];
        $hasChanges = false;
        foreach ($watchedFields as $field) {
            $old = str_replace("\r\n", "\n", trim((string) ($shop->$field ?? '')));
            $new = str_replace("\r\n", "\n", trim((string) ($data[$field] ?? '')));
            if ($old !== $new) {
                $hasChanges = true;
                break;
            }
        }

        if (!$hasChanges) {
            return redirect()->to('seller/shop')->with('message', 'Aucune modification détectée.');
        }

        if ($this->sellerModel->update($userId, $data)) {
            return redirect()->to('seller/shop')->with('message', 'Informations mises à jour.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', implode('<br>', $this->sellerModel->errors()));
    }
}
