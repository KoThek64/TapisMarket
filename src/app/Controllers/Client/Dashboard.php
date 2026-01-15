<?php

namespace App\Controllers\Client;

class Dashboard extends ClientBaseController
{
    public function index()
    {
        $userId = user_id();

        $data = array_merge($this->clientData, [
            'title' => 'Mon Tableau de Bord',
            'subtitle' => 'Bienvenue sur votre espace personnel, ' . esc($user->firstname ?? 'Client'),
            'theme' => 'accent',
            'initial' => substr($user->firstname ?? 'C', 0, 1),
            'stats' => [
                'Total Commandes' => $totalOrders ?? 0,
                'Avis refusés' => count($rejectedReviews ?? [])
            ],
            'recentOrders' => $this->orderModel->getUserOrders($userId, 5),
            'totalOrders' => $this->orderModel->countUserOrders($userId),
            'publishedReviewsCount' => $this->reviewModel->countPublishedReviewsForUser($userId),
        ]);

        return view('pages/client/dashboard', $data);
    }
}
