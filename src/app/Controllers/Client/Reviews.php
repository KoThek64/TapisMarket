<?php

namespace App\Controllers\Client;

use App\Models\ProductModel;
use Exception;

class Reviews extends ClientBaseController
{
    // Liste des avis
    public function index()
    {
        $user = user_data();

        $reviews = $this->reviewModel->getPaginatedReviewsForUser($user->user_id, 8);

        $data = array_merge($this->clientData, [
            'title' => 'Mes Avis',
            'subtitle' => 'Vos contributions à la communauté',
            'theme' => 'accent',
            'initial' => substr($user->firstname ?? 'C', 0, 1),
            'reviews' => $reviews,
            'pager' => $this->reviewModel->pager
        ]);

        return view('client/reviews/index', $data);
    }

    // Formulaire
    public function edit($productId = null)
    {
        if (empty($productId)) {
            return redirect()->back()->with('error', 'Produit non spécifié.');
        }

        if (!$this->orderItemModel->hasUserPurchasedProduct(user_id(), $productId)) {
            return redirect()->to('client/orders')->with('error', 'Vous devez avoir acheté et reçu ce produit pour laisser un avis.');
        }

        $product = $this->productModel->find($productId);

        if (!$product) {
            return redirect()->to('client/orders')->with('error', 'Produit introuvable.');
        }

        $existingReview = $this->reviewModel->getReviewForProductByUser(user_id(), $productId);

        $data = array_merge($this->clientData, [
            'custom_error_alert' => true,
            'title' => $existingReview ? 'Modifier mon avis' : 'Laisser un avis',
            'subtitle' => 'Partagez votre expérience',
            'theme' => 'accent',
            'initial' => 'A',
            'product' => $product,
            'existingReview' => $existingReview,
        ]);

        return view('client/reviews/edit', $data);
    }

    // Traitement
    public function update()
    {

        $userId = user_id();

        $rules = [
            'product_id' => 'required|integer',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'comment' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = $this->request->getPost('product_id');

        // CORRECTION ICI AUSSI
        if (!$this->orderItemModel->hasUserPurchasedProduct($userId, $productId)) {
            return redirect()->back()->with('error', 'Action non autorisée. Vous n\'avez pas commandé ce produit.');
        }

        $existingReview = $this->reviewModel
            ->where('customer_id', $userId)
            ->where('product_id', $productId)
            ->first();

        $input = [
            'product_id' => $productId,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'customer_id' => $userId,
            'moderation_status' => 'PUBLISHED',
            'published_at' => date('Y-m-d H:i:s')
        ];

        $message = 'Votre avis a été publié avec succès.';

        if ($existingReview) {
            $input['id'] = $existingReview->id;
            $message = 'Votre avis a été mis à jour avec succès.';
        }

        try {
            if ($this->reviewModel->save($input)) {
                return redirect()->to('client/reviews')->with('message', $message);
            } else {
                $errors = $this->reviewModel->errors();
                return redirect()->back()->withInput()->with('error', 'Erreur sauvegarde: ' . implode(', ', $errors));
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Erreur système: ' . $e->getMessage());
        }
    }
}
