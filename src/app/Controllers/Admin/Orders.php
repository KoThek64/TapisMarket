<?php

namespace App\Controllers\Admin;

use CodeIgniter\Exceptions\PageNotFoundException;

class Orders extends AdminBaseController
{
    // Affichage de la liste des commandes
    public function index()
    {
        $statusFilter = $this->request->getGet('status');

        $data = array_merge($this->adminData, [
            'title' => 'Gestion des Commandes',
            'subtitle' => 'Vue d\'ensemble des transactions',
            'orders' => $this->orderModel->getAllOrdersWithClient(10, $statusFilter),
            'pager' => $this->orderModel->pager,
            'globalAmount' => $this->orderModel->getGlobalTotalAmount(),
            'statusList' => $this->orderModel->getOrderStatuses(),
            'currentStatus' => $statusFilter,
        ]);

        return view('admin/orders/index', $data);
    }

    // Affichage du détail d'une commande
    public function show($id)
    {
        $order = $this->orderModel->getOrderWithIdentity($id);

        if (!$order) {
            throw PageNotFoundException::forPageNotFound("Order #$id not found.");
        }

        $data = array_merge($this->adminData, [
            'title' => 'Order Detail ' . $order->reference,
            'subtitle' => 'Détails de la transaction',
            'order' => $order,
        ]);

        return view('admin/orders/detail', $data);
    }
}
