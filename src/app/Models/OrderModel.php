<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Order;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $returnType       = Order::class;

    protected $allowedFields    = [
        'customer_id', 'reference', 'order_date', 'status',
        'delivery_method', 'delivery_street', 'delivery_postal_code', 
        'delivery_city', 'delivery_country', 'total_ttc', 'shipping_fees'
    ];

    protected $beforeInsert = ['generateReference'];

    protected $validationRules = [
        'total_ttc' => 'required|decimal|greater_than[0]',
        'status'    => 'in_list['.ORDER_PENDING.','.ORDER_PAID.','.ORDER_PREPARING.','.ORDER_SHIPPED.','.ORDER_DELIVERED.','.ORDER_CANCELLED.']',
        'reference' => 'is_unique[orders.reference]', 
    ];



    // Get all orders with client info for admin
    public function getAllOrdersWithClient(int $perPage = 15, ?string $status = null)
    {
        $builder = $this->select('orders.*, users.lastname, users.firstname, users.email') 
                        ->join('customers', 'customers.user_id = orders.customer_id', 'left') 
                        ->join('users', 'users.id = customers.user_id', 'left')
                        ->orderBy('order_date', 'DESC');

        if ($status && array_key_exists($status, $this->getOrderStatuses())) {
            $builder->where('orders.status', $status);
        }

        return $builder->paginate($perPage, 'orders');
    }

    // Calculate global total amount
    public function getGlobalTotalAmount(): float
    {
        $result = $this->selectSum('total_ttc')->get()->getRow();
        return (float) ($result->total_ttc ?? 0.0);
    }

    // For clean display of statuses in views
    public function getOrderStatuses(): array
    {
        return [
            ORDER_PENDING   => 'En attente de validation',
            ORDER_PAID      => 'Payée',
            ORDER_PREPARING => 'En préparation',
            ORDER_SHIPPED   => 'Expédiée',
            ORDER_DELIVERED => 'Livrée',
            ORDER_CANCELLED => 'Annulée',
        ];
    }


    // Order history for a client
    public function getCustomerHistory(int $customerId)
    {
        return $this->where('customer_id', $customerId)
                    ->orderBy('order_date', 'DESC')
                    ->findAll();
    }

    // Find order by reference
    public function getByReference(string $reference)
    {
        return $this->where('reference', $reference)->first();
    }

    // Complete details for invoice or detail page
    public function getOrderWithIdentity(int $orderId)
    {
        return $this->select('orders.*, users.lastname, users.firstname, users.email, customers.phone')
                    ->join('customers', 'customers.user_id = orders.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->find($orderId);
    }

    protected function generateReference(array $data)
    {
        if (!isset($data['data']['reference'])) {
            $ref = 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
            $data['data']['reference'] = $ref;
        }
        return $data;
    }

    // For quick stats
    public function getPendingOrders()
    {
        return $this->where('status', ORDER_PENDING)->countAllResults();
    }

    // Count valid orders (not cancelled)
    public function countValidOrders()
    {
        return $this->where('status !=', ORDER_CANCELLED)->countAllResults();
    }

    // Get total sales amount
    public function getTotalSales()
    {
        $result = $this->selectSum('total_ttc')
                       ->where('status !=', ORDER_CANCELLED)
                       ->get()
                       ->getRow();
        return (float) ($result->total_ttc ?? 0.0);
    }

    // Get recent orders
    public function getRecentOrders(int $limit = 5)
    {
        return $this->select('orders.*, users.lastname, users.firstname')
                    ->join('customers', 'customers.user_id = orders.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->orderBy('order_date', 'DESC')
                    ->findAll($limit);
    }
}