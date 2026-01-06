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

    // List of sales
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

    // Sales counter for sellers
    public function countSellerSales(int $sellerId): int
    {
        return $this->join('products', 'products.id = order_items.product_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('orders.status !=', 'CANCELLED')
                    ->countAllResults();
    }

    // Seller turnover
    public function getSellerTurnover(int $sellerId): float
    {
        $validStatuses = ['PAID', 'PREPARING', 'SHIPPED', 'DELIVERED'];

        $result = $this->selectSum('order_items.unit_price * order_items.quantity', 'total_turnover')
                       ->join('products', 'products.id = order_items.product_id')
                       ->join('orders', 'orders.id = order_items.order_id')
                       ->where('products.seller_id', $sellerId)
                       ->whereIn('orders.status', $validStatuses)
                       ->first();

        return $result->total_turnover ?? 0.00;
    }

    // Best selling products for this seller (top 3)
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
    public function getOrderItems(int $orderId)
    {
        return $this->select('order_items.*, products.title, products.alias, product_photos.file_name as image')
                    ->join('products', 'products.id = order_items.product_id')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('order_id', $orderId)
                    ->findAll();
    }
}