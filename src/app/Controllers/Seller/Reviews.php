<?php

namespace App\Controllers\Seller;

class Reviews extends SellerBaseController
{
    // Modèle pour les avis
    public function index()
    {
        $userId = $this->getSellerId();
        $sort = $this->request->getGet('sort') ?? 'date_desc';

        $stats = $this->reviewModel->getSellerGlobalStats($userId);

        $reviews = $this->reviewModel->getSellerReviews($userId, 10, $sort);

        $data = array_merge($this->sellerData, [
            'reviews' => $reviews,
            'pager' => $this->reviewModel->pager,
            'stats' => $stats,
            'currentSort' => $sort,
            'title' => 'Avis Clients',
            'subtitle' => 'Découvrez ce que les clients pensent de vos produits.'
        ]);

        return view('seller/reviews/index', $data);
    }
}
