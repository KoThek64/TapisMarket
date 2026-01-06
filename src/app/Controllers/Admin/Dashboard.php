<?php

namespace App\Controllers\Admin;

class Dashboard extends AdminBaseController
{
    public function index()
    {
        $pendingProductsCount = $this->adminData['pendingProductsCount'];
        $pendingSellersCount = $this->adminData['pendingSellersCount'];

        $moderationLink = site_url('admin/products');
        if ($pendingProductsCount == 0 && $pendingSellersCount > 0) {
            $moderationLink = site_url('admin/users');
        }

        $latestUsers = $this->userModel->asObject('stdClass')->getLatestRegistered(5);
        $latestOrders = $this->orderModel->asObject('stdClass')->getRecentOrders(5);

        $data = array_merge($this->adminData, [
            'title'                => 'Dashboard',
            'usersCount'           => $this->userModel->countAllUsers(),
            'ordersCount'          => $this->orderModel->countValidOrders(),
            'totalSales'           => $this->orderModel->getTotalSales(),
            'latestUsers'          => $latestUsers,
            'latestOrders'         => $latestOrders,
            'moderationLink'       => $moderationLink,
            'pendingSellersCount'  => $pendingSellersCount,
            'pendingProductsCount' => $pendingProductsCount
        ]);

        return view('admin/dashboard', $data);
    }
}