<?php

namespace App\Controllers\Seller;

class Orders extends SellerBaseController
{
    // Modèle pour les items de commande
    public function index()
    {
        $userId = $this->getSellerId();
        $statusFilter = $this->request->getGet('status');

        $totalTurnover = $this->orderItemModel->getSellerTurnover($userId);
        $totalOrders = $this->orderItemModel->getSellerTotalOrders($userId);
        $statusCounts = $this->orderItemModel->countOrdersByStatus($userId);

        $pagedOrders = $this->orderItemModel->getSellerOrders($userId, 10, $statusFilter);
        $pager = $this->orderItemModel->pager;

        $orderIds = [];
        foreach ($pagedOrders as $po) {
            $orderIds[] = $po->id;
        }

        $orders = [];

        if (!empty($orderIds)) {

            $sales = $this->orderItemModel->getItemsForOrders($userId, $orderIds);

            foreach ($sales as $sale) {
                $orderId = $sale->order_id;


                if (!isset($orders[$orderId])) {
                    $orders[$orderId] = [
                        'info' => $sale,
                        'items' => []
                    ];
                }

                $orders[$orderId]['items'][] = $sale;
            }
        }

        $data = array_merge($this->sellerData, [
            'orders' => $orders,
            'pager' => $pager,
            'currentStatus' => $statusFilter,
            'statusCounts' => $statusCounts,
            'stats' => [
                'turnover' => $totalTurnover,
                'count' => $totalOrders
            ],
            'title' => 'Mes Commandes',
            'subtitle' => 'Suivez les achats de vos produits et votre chiffre d\'affaires.'
        ]);

        return view('seller/orders/index', $data);
    }

    // Affiche les détails d'une commande
    public function show($id = null)
    {
        $userId = $this->getSellerId();

        // Récupère les items de CETTE commande pour CE vendeur
        $items = $this->orderItemModel->getItemsForOrders($userId, [$id]);

        if (empty($items)) {
            return redirect()->to('seller/orders')->with('error', 'Commande introuvable.');
        }

        $order = [
            'info' => $items[0],
            'items' => $items
        ];

        return view('seller/orders/show', ['order' => $order]);
    }

    // Change le statut d'une commande
    public function updateStatus($id)
    {
        $userId = $this->getSellerId();
        $newStatus = $this->request->getPost('status');

        $allowedStatuses = [
            'PREPARING' => 'En préparation',
            'SHIPPED' => 'Expédiée',
            'DELIVERED' => 'Livrée'
        ];

        if (!array_key_exists($newStatus, $allowedStatuses)) {
            return redirect()->back()->with('error', 'Statut invalide.');
        }

        $items = $this->orderItemModel->getItemsForOrders($userId, [$id]);

        if (empty($items)) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        // Utilisation du modèle via lazy loading
        $currentOrder = $this->orderModel->find($id);

        if (in_array($currentOrder->status, ['CANCELLED', 'REFUNDED'])) {
            return redirect()->back()->with('error', 'Impossible de modifier une commande annulée.');
        }

        $this->orderModel->update($id, ['status' => $newStatus]);

        return redirect()->back()->with('message', "Statut mis à jour : " . $allowedStatuses[$newStatus]);
    }

    // Ancienne méthode (pour compatibilité si liens existants)
    public function ship($id)
    {
        // Redirige vers la nouvelle méthode avec le bon paramètre
        $this->request->setGlobal('post', ['status' => 'SHIPPED']);
        return $this->updateStatus($id);
    }
}
