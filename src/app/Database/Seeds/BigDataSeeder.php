<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Exception;

class BigDataSeeder extends Seeder
{
    //config
    const NUM_SELLERS = 1000;
    const NUM_CLIENTS = 50000;
    const NUM_PRODUCTS = 100000;
    const NUM_ORDERS = 80000;

    const BATCH_SIZE = 3000;

    public function run()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
        $this->db->query("SET UNIQUE_CHECKS = 0");
        $this->db->query("SET AUTOCOMMIT = 0");

        echo "debut";

        $tables = ['reviews', 'order_items', 'orders', 'cart_items', 'carts', 'product_photos', 'products', 'categories', 'addresses', 'sellers', 'customers', 'administrators', 'users'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table))
                $this->db->table($table)->truncate();
        }

        $passHash = password_hash('1234', PASSWORD_DEFAULT);
        $idUser = 1;

        echo "User";
        $this->db->transStart();

        // Admins
        $users = [];
        $admins = [];
        $sellers = [];

        $users[] = ['id' => $idUser, 'email' => 'admin@tapis.com', 'password' => $passHash, 'firstname' => 'Sys', 'lastname' => 'Admin', 'role' => 'ADMIN', 'created_at' => date('Y-m-d H:i:s')];
        $admins[] = ['user_id' => $idUser];
        $idUser++;

        $users[] = ['id' => $idUser, 'email' => 'vendeur@tapis.com', 'password' => $passHash, 'firstname' => 'Vendeur', 'lastname' => 'Test', 'role' => 'SELLER', 'created_at' => date('Y-m-d H:i:s')];
        $sellers[] = ['user_id' => $idUser, 'shop_name' => 'Boutique Test', 'siret' => '99999999900001', 'status' => 'VALIDATED', 'shop_description' => 'Test shop'];
        $idUser++;

        //Vendeurs
        for ($i = 0; $i < self::NUM_SELLERS; $i++) {
            $status = ($i % 50 === 0) ? 'PENDING_VALIDATION' : 'VALIDATED';

            $users[] = [
                'id' => $idUser,
                'email' => "s$i@shop.com",
                'password' => $passHash,
                'firstname' => "S$i",
                'lastname' => "Seller",
                'role' => 'SELLER',
                'created_at' => date('Y-m-d H:i:s')
            ];
            $sellers[] = [
                'user_id' => $idUser,
                'shop_name' => "Shop $i",
                'siret' => str_pad($i, 14, '0', STR_PAD_LEFT),
                'status' => $status,
                'shop_description' => 'Description'
            ];
            $idUser++;

            if (count($users) >= self::BATCH_SIZE) {
                $this->db->table('users')->insertBatch($users);
                $this->db->table('sellers')->insertBatch($sellers);
                $users = [];
                $sellers = [];
                echo "S";
            }
        }
        if (!empty($users)) {
            $this->db->table('users')->insertBatch($users);
            $this->db->table('sellers')->insertBatch($sellers);
        }

        // Clients 
        $users = [];
        $customers = [];
        $firstClientId = $idUser;

        for ($i = 0; $i < self::NUM_CLIENTS; $i++) {
            //variation date pour tester dernier inscit
            $date = date('Y-m-d H:i:s', strtotime("-" . ($i % 30) . " days -" . ($i % 60) . " minutes"));

            $users[] = [
                'id' => $idUser,
                'email' => "c$i@mail.com",
                'password' => $passHash,
                'firstname' => "John$i",
                'lastname' => "Doe",
                'role' => 'CUSTOMER',
                'created_at' => $date
            ];
            $customers[] = [
                'user_id' => $idUser,
                'phone' => '0600000000'
            ];
            $idUser++;

            if (count($users) >= self::BATCH_SIZE) {
                $this->db->table('users')->insertBatch($users);
                $this->db->table('customers')->insertBatch($customers);
                $users = [];
                $customers = [];
            }
        }
        if (!empty($users)) {
            $this->db->table('users')->insertBatch($users);
            $this->db->table('customers')->insertBatch($customers);
        }
        if (!empty($admins))
            $this->db->table('administrators')->insertBatch($admins);

        $this->db->transComplete();


        echo "Produits";
        $this->db->transStart();

        $cats = [];
        for ($k = 1; $k <= 10; $k++)
            $cats[] = ['name' => "Cat $k", 'alias' => "cat-$k"];
        $this->db->table('categories')->insertBatch($cats);

        $products = [];
        $photos = [];
        $idProd = 1;

        for ($i = 0; $i < self::NUM_PRODUCTS; $i++) {
            $sid = ($i % self::NUM_SELLERS) + 2;

            $status = ($i % 50 === 0) ? 'PENDING_VALIDATION' : 'APPROVED';

            $products[] = [
                'id' => $idProd,
                'seller_id' => $sid,
                'category_id' => ($i % 10) + 1,
                'title' => "Tapis $i",
                'alias' => "tapis-$i",
                'short_description' => "Super tapis",
                'long_description' => "Desc",
                'price' => ($i % 500) + 50,
                'stock_available' => 100,
                'dimensions' => "200x300",
                'product_status' => $status,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $photos[] = [
                'product_id' => $idProd,
                'file_name' => 'default.jpg',
                'display_order' => 1
            ];
            $idProd++;

            if (count($products) >= self::BATCH_SIZE) {
                $this->db->table('products')->insertBatch($products);
                $this->db->table('product_photos')->insertBatch($photos);
                $products = [];
                $photos = [];
            }
        }
        if (!empty($products)) {
            $this->db->table('products')->insertBatch($products);
            $this->db->table('product_photos')->insertBatch($photos);
        }
        $this->db->transComplete();

        //Avis
        echo "Avis";
        $this->db->transStart();

        $orders = [];
        $items = [];
        $reviews = [];
        $idOrder = 1;
        $maxProdId = self::NUM_PRODUCTS;
        $maxClientId = $idUser - 1;

        for ($i = 0; $i < self::NUM_ORDERS; $i++) {
            $cid = rand($firstClientId, $maxClientId);

            $orders[] = [
                'id' => $idOrder,
                'customer_id' => $cid,
                'reference' => "CMD-$i",
                'order_date' => date('Y-m-d H:i:s'),
                'status' => 'PAID',
                'total_ttc' => 100,
                'shipping_fees' => 10,
                'delivery_method' => 'Std',
                'delivery_street' => 'Rue',
                'delivery_postal_code' => '75000',
                'delivery_city' => 'Paris',
                'delivery_country' => 'FR'
            ];

            $pid = rand(1, $maxProdId);
            $items[] = [
                'order_id' => $idOrder,
                'product_id' => $pid,
                'quantity' => 1,
                'unit_price' => 100
            ];

            if ($i % 2 == 0) {
                $reviews[] = [
                    'customer_id' => $cid,
                    'product_id' => $pid,
                    'rating' => 5,
                    'comment' => 'Top',
                    'moderation_status' => 'PUBLISHED',
                    'published_at' => date('Y-m-d H:i:s')
                ];
            }

            $idOrder++;

            if (count($orders) >= self::BATCH_SIZE) {
                $this->db->table('orders')->insertBatch($orders);
                $this->db->table('order_items')->insertBatch($items);
                try {
                    if (!empty($reviews))
                        $this->db->table('reviews')->insertBatch($reviews);
                } catch (Exception $e) {
                }

                $orders = [];
                $items = [];
                $reviews = [];
            }
        }
        if (!empty($orders)) {
            $this->db->table('orders')->insertBatch($orders);
            $this->db->table('order_items')->insertBatch($items);
            try {
                if (!empty($reviews))
                    $this->db->table('reviews')->insertBatch($reviews);
            } catch (Exception $e) {
            }
        }

        $this->db->transComplete();

        $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
        $this->db->query("SET UNIQUE_CHECKS = 1");
        $this->db->query("COMMIT");
        $this->db->query("SET AUTOCOMMIT = 1");

        echo "Fin";
    }
}
