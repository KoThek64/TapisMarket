<?php

namespace App\Controllers\Client;

class Orders extends ClientBaseController
{
    // Liste des commandes
    public function index()
    {
        $userId = user_id();

        // Utilisation magique de orderModel
        $orders = $this->orderModel->getPaginatedOrdersForClient($userId, 10);

        $data = array_merge($this->clientData, [
            'title' => 'Mes Commandes',
            'subtitle' => 'Retrouvez l\'historique de vos achats',
            'theme' => 'accent',
            'initial' => substr($user->firstname ?? 'C', 0, 1),
            'orders' => $orders,
            'pager' => $this->orderModel->pager,
        ]);

        return view('client/orders/index', $data);
    }

    // Détail d'une commande
    public function show($id = null)
    {
        $userId = user_id();
        $order = $this->orderModel->find($id);

        if (!$order || $order->customer_id !== $userId) {
            return redirect()->to('client/orders')->with('error', 'Commande introuvable.');
        }

        $items = $this->orderItemModel->getPaginatedOrderItems($id, 10);

        $data = array_merge($this->clientData, [
            'title' => 'Commande #' . esc($order->reference ?? $order->id),
            'subtitle' => 'Passée le ' . (!empty($order->order_date) ? date('d/m/Y à H:i', strtotime((string) $order->order_date)) : 'Date inconnue'),
            'theme' => 'accent',
            'initial' => '#',
            'order' => $order,
            'items' => $items,
            'pager' => $this->orderItemModel->pager
        ]);

        return view('client/orders/show', $data);
    }
}