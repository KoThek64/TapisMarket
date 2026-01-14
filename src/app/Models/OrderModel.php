<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Order;
use App\Enums\ShippingType;
use App\Libraries\Factories\ShippingStrategyFactory;

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

    // Client: Get orders for logged user
    public function getUserOrders(int $userId, int $perPage = 10)
    {
        return $this->where('customer_id', $userId)
                    ->orderBy('order_date', 'DESC')
                    ->paginate($perPage);
    }

    // Client: Count orders
    public function countUserOrders(int $userId)
    {
        return $this->where('customer_id', $userId)->countAllResults();
    }

    /**
     * Crée une commande complète à partir du panier, gère les stocks et vide le panier
     * Retourne l'ID de la commande si succès, false sinon.
     */
    public function createOrderFromCart(int $customerId, array $orderData, $cart, array $items)
    {
        $this->db->transStart();

        // 1. Création de la commande
        $orderId = $this->insert($orderData);
        if (!$orderId) {
            $this->db->transRollback();
            return false;
        }

        $orderItemModel = new OrderItemModel();
        $productModel = new ProductModel();
        $cartItemModel = new CartItemModel();
        $cartModel = new CartModel();

        // 2. Transfert des articles et mise à jour des stocks
        foreach ($items as $item) {
            // Création de la ligne de commande
            $orderItemModel->insert([
                'order_id'   => $orderId,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'unit_price' => $item->price
            ]);

            // Décrémentation du stock via le modèle produit
            $productModel->decrementStock($item->product_id, $item->quantity);
        }

        // 3. Calcul des frais de port via Strategy Pattern
        $methodStr = strtolower($orderData['delivery_method'] ?? 'standard');
        $shippingType = ShippingType::tryFrom($methodStr) ?? ShippingType::STANDARD;
        
        $strategy = ShippingStrategyFactory::create($shippingType);
        
        // Recharger la commande pour que la stratégie puisse compter les articles
        $order = $this->find($orderId);
        $shippingCost = $strategy->calculate($order);
        
        // Mise à jour du total
        $newTotal = $orderData['total_ttc'] + $shippingCost;
        
        if (!$this->update($orderId, [
            'shipping_fees' => $shippingCost,
            'total_ttc'     => $newTotal
        ])) {
            $this->db->transRollback();
            return false;
        }

        // 4. Vider le panier
        $cartItemModel->where('cart_id', $cart->id)->delete();
        $cartModel->updateTotal($cart->id);

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return false;
        }

        return $orderId;
    }

    

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
    public function getOrderWithIdentity($orderId)
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

    public function getPaginatedOrdersForClient(int $clientId, int $perPage = 10): array
    {
        return $this->where('customer_id', $clientId)
                    ->orderBy('order_date', 'DESC')
                    ->paginate($perPage);
    }
    
    // Get item count for a specific order
    public function getItemCount(int $orderId): int
    {
        $orderItemModel = new OrderItemModel();
        
        $result = $orderItemModel->select('Sum(quantity) as quantity')
                                 ->where('order_id', $orderId)
                                 ->get()
                                 ->getRow();
                                 
        return (int) ($result->quantity ?? 0);
    }
}
