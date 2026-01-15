<?php

namespace App\Controllers\Seller;

class Dashboard extends SellerBaseController
{
    // Affiche le tableau de bord vendeur
    public function index()
    {
        $userId = $this->getSellerId();

        $totalRevenue = $this->orderItemModel->getSellerTurnover($userId);
        $totalOrders = $this->orderItemModel->getSellerTotalOrders($userId);

        $pendingProducts = $this->productModel->countSellerPendingProducts($userId);

        $ratingData = $this->reviewModel->getSellerAverageRating($userId);
        $averageRating = $ratingData['average'];
        $countRating = $ratingData['count'];

        $recentSales = $this->orderItemModel->getSellerSales($userId, 5);
        if (!$recentSales)
            $recentSales = [];

        $user = $this->sellerModel->find($userId);

        $data = array_merge($this->sellerData, [
            'title' => 'Tableau de Bord',
            'subtitle' => 'Vue d\'ensemble de votre activité',
            'theme' => 'accent',
            'initial' => substr($user->firstname ?? 'S', 0, 1),
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'pendingProducts' => $pendingProducts,
            'averageRating' => $averageRating,
            'countRating' => $countRating,
            'recentSales' => $recentSales,
        ]);

        return view('seller/dashboard', $data);
    }
}
