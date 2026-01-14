<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OrderItem;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $returnType       = OrderItem::class;

    protected $allowedFields    = [
        'id', 
        'order_id', 
        'product_id', 
        'quantity', 
        'unit_price'
    ];

    protected $validationRules = [
        'quantity'      => 'required|integer|greater_than[0]',
        'unit_price'    => 'required|decimal|greater_than_equal_to[0]',
    ];

    // Recupere les ID de commande uniques pagines.
    public function getSellerOrders(int $sellerId, int $perPage = 5, ?string $status = null)
    {
        $builder = $this->select('orders.id')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->distinct()
                    ->orderBy('orders.id', 'DESC'); 
        
        if ($status && $status !== 'ALL') {
             $builder->where('orders.status', $status);
        }

        return $builder->paginate($perPage);
    }

    // Recupere tous les articles pour la liste specifique d'ID de commandes.
    public function getItemsForOrders(int $sellerId, array $orderIds)
    {
        if (empty($orderIds)) return [];

        return $this->select('
                        order_items.*, 
                        products.title, 
                        products.alias,
                        orders.order_date, 
                        orders.status, 
                        orders.reference,
                        orders.delivery_street, 
                        orders.delivery_postal_code, 
                        orders.delivery_city, 
                        orders.delivery_country,
                        users.lastname as customer_lastname, 
                        users.firstname as customer_firstname
                    ')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->join('customers', 'customers.user_id = orders.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->where('products.seller_id', $sellerId)
                    ->whereIn('orders.id', $orderIds)
                    ->orderBy('orders.order_date', 'DESC')
                    ->findAll();
    }

    // liste des ventes
    public function getSellerSales(int $sellerId, int $perPage = 10)
    {
        return $this->select('
                        order_items.*, 
                        products.title, 
                        products.alias,
                        orders.order_date, 
                        orders.status, 
                        orders.reference,
                        orders.delivery_street, 
                        orders.delivery_postal_code, 
                        orders.delivery_city, 
                        orders.delivery_country,
                        users.lastname as customer_lastname, 
                        users.firstname as customer_firstname
                    ')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->join('customers', 'customers.user_id = orders.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->orderBy('orders.order_date', 'DESC')
                    ->paginate($perPage);
    }

    // compteur de ventes pour les vendeurs
    public function countSellerSales(int $sellerId): int
    {
        return $this->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->countAllResults();
    }

    // chiffre d'affaire du vendeur
    public function getSellerTurnover(int $sellerId): float
    {
        $validStatuses = ['PAID', 'PREPARING', 'SHIPPED', 'DELIVERED'];

        $result = $this->select('SUM(order_items.unit_price * order_items.quantity) as total_turnover')
                       ->join('products', 'products.id = order_items.product_id')
                       ->join('orders', 'orders.id = order_items.order_id')
                       ->where('products.seller_id', $sellerId)
                       ->whereIn('orders.status', $validStatuses)
                       ->first();

        return $result->total_turnover ?? 0.00;
    }

    // total des commandes pour le vendeur
    public function getSellerTotalOrders(int $sellerId): int
    {
        return $this->select('order_items.order_id')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->distinct()
                    ->countAllResults();
    }

    // produits les mieux vendus pour ce vendeur (top 3)
    public function getSellerBestSellers(int $sellerId, int $limit = 3)
    {
        return $this->select('products.title, SUM(order_items.quantity) as total_sold')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->groupBy('order_items.product_id')
                    ->orderBy('total_sold', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }


    // For order detail page
    public function getPaginatedOrderItems(int $orderId, int $perPage = 10)
    {
        return $this->select('order_items.*, products.title, products.alias, product_photos.file_name as image')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('order_id', $orderId)
                    ->paginate($perPage);
    }

    public function hasUserPurchasedProduct(int $userId, int $productId): bool
    {
        $allowedStatuses = ['PAID', 'PREPARING', 'SHIPPED', 'DELIVERED'];

        $count = $this->select('order_items.id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('orders.customer_id', $userId)
                    ->where('order_items.product_id', $productId)
                    ->whereIn('orders.status', $allowedStatuses)
                    ->countAllResults();

        return $count > 0;
    }

    public function countOrdersByStatus(int $sellerId)
    {
        $query = $this->db->table('order_items')
            ->select('orders.status, COUNT(DISTINCT orders.id) as count')
            ->join('products', 'products.id = order_items.product_id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('products.seller_id', $sellerId)
            ->where('orders.status !=', 'CANCELLED')
            ->groupBy('orders.status')
            ->get();

        $results = $query->getResult();
        $counts = [];
        foreach ($results as $row) {
            $counts[$row->status] = $row->count;
        }
        return $counts;
    }
}
