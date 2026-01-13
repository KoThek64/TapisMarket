<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run()
    {
        helper('url');

        $this->db->disableForeignKeyChecks();
        $tables = ['reviews', 'order_items', 'orders', 'cart_items', 'carts', 'product_photos', 'products', 'categories', 'addresses', 'sellers', 'customers', 'administrators', 'users'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->truncate();
            }
        }
        $this->db->enableForeignKeyChecks();

        
        $adminIds = [];
        $adminIds[] = $this->createUser('admin@tapis.com', 'System', 'Admin', 'ADMIN');
        $adminIds[] = $this->createUser('staff@tapis.com', 'Moderator', 'Marc', 'ADMIN');
        foreach ($adminIds as $id) {
            $this->db->table('administrators')->insert(['user_id' => $id]);
        }

        
        $sellerIds = [];
        $shops = [
            ['name' => 'Oriental Rugs', 'desc' => 'Silk and wool specialist.'],
            ['name' => 'Berber Crafts', 'desc' => 'Direct import from Morocco.'],
            ['name' => 'Modern Rugs Co', 'desc' => 'Contemporary designs.'],
            ['name' => 'Luxury & Tradition', 'desc' => 'Rare collection rugs.'],
            ['name' => 'Eco-Rugs', 'desc' => 'Recycled and natural materials.'],
        ];

        foreach ($shops as $idx => $b) {
            $vid = $this->createUser("seller$idx@mail.com", "Name$idx", "Surname$idx", "SELLER");
            $sellerIds[] = $vid;
            $sellerStatus = (rand(1, 10) > 3) ? 'VALIDATED' : 'PENDING_VALIDATION'; 
            $this->db->table('sellers')->insert([
                'user_id'          => $vid,
                'shop_name'        => $b['name'],
                'siret'            => '123456789' . str_pad($idx, 5, '0', STR_PAD_LEFT),
                'status'           => $sellerStatus, 
                'shop_description' => $b['desc']
            ]);
        }

        
        $clientIds = [];
        for ($i = 0; $i < 30; $i++) {
            $cid = $this->createUser("client$i@mail.com", "ClientName$i", "ClientSurname$i", "CUSTOMER");
            $clientIds[] = $cid;
            $this->db->table('customers')->insert([
                'user_id'   => $cid,
                'phone'     => '06' . str_pad($i, 8, '0', STR_PAD_LEFT)
            ]);
        }

        
        $categories = [
            ['name' => 'Traditional Persian', 'alias' => 'traditional-persian'],
            ['name' => 'Berber & Kilim', 'alias' => 'berber-kilim'],
            ['name' => 'Scandinavian Design', 'alias' => 'scandinavian-design'],
            ['name' => 'Kids Room', 'alias' => 'kids-room'],
            ['name' => 'Vintage & Worn', 'alias' => 'vintage-worn'],
            ['name' => 'Outdoor & Garden', 'alias' => 'outdoor-garden'],
            ['name' => 'Luxury Silk', 'alias' => 'luxury-silk'],
            ['name' => 'Shaggy & Fluffy', 'alias' => 'shaggy-fluffy'],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $this->db->table('categories')->insert($cat);
            $catIds[] = $this->db->insertID();
        }

       
        $productIds = [];
        $rug_prefixes = ['Royal', 'Antique', 'Minimalist', 'Boho', 'Abstract', 'Classic', 'Imperial', 'Ethnic'];
        $rug_suffixes = ['Tapestry', 'Wool Rug', 'Hand-woven', 'Silk Piece', 'Carpet', 'Flatweave'];
        $materials = ['Cotton', 'Wool', 'Polyester', 'Polypropylene', 'Jean', 'Jute', 'Wool and cotton', 'Polypropylene and polyester', 'Viscose'];


        foreach ($sellerIds as $vid) {
            for ($p = 0; $p < 8; $p++) {
                $name = $rug_prefixes[array_rand($rug_prefixes)] . ' ' . $rug_suffixes[array_rand($rug_suffixes)] . ' ' . uniqid();
                $price = rand(80, 2500);
                $stock = rand(0, 20);
                $cat = $catIds[array_rand($catIds)];
                $status = (rand(0, 10) > 1) ? 'APPROVED' : 'PENDING_VALIDATION';
                $mat = $materials[array_rand($materials)];
                $productIds[] = $this->createProduct($vid, $cat, $name, $price, $stock, $status, $mat);
            }
        }

        
        foreach ($clientIds as $cid) {
            $nbOrders = rand(1, 4);
            for ($c = 0; $c < $nbOrders; $c++) {
                $pId = $productIds[array_rand($productIds)];
                $qty = rand(1, 2);
                $price = rand(150, 600);
                $days = rand(1, 365);
                $this->createOrder($cid, $pId, $qty, $price, "-$days days", 'PAID');
            }
        }

        
        $possibleComments = [
            "Great product, I recommend!",
            "Delivery a bit long but the rug is magnificent.",
            "Very disappointed by the color which does not quite match the photo, too bad.",
            "I ordered this rug for my living room and I am generally satisfied... We'll see how it ages."
        ];

        $existingReviews = []; 

        for ($a = 0; $a < 60; $a++) {
            $cid = $clientIds[array_rand($clientIds)];
            $pid = $productIds[array_rand($productIds)];
            $key = $cid . '-' . $pid;

            if (in_array($key, $existingReviews)) {
                continue; 
            }

            $existingReviews[] = $key;

            $this->db->table('reviews')->insert([
                'customer_id'       => $cid,
                'product_id'        => $pid,
                'rating'            => rand(1, 5),
                'comment'           => $possibleComments[array_rand($possibleComments)],
                'moderation_status' => (rand(0, 10) > 2) ? 'PUBLISHED' : 'REFUSED', 
                'published_at'      => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
            ]);
        }
    }

    private function createUser($email, $lastname, $firstname, $role) {
        $this->db->table('users')->insert([
            'email'      => $email,
            'password'   => password_hash('123456', PASSWORD_DEFAULT),
            'lastname'   => $lastname,
            'firstname'  => $firstname,
            'role'       => $role,
            'created_at' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 12) . ' months'))
        ]);
        return $this->db->insertID();
    }

    private function createProduct($seller, $cat, $title, $price, $stock, $status, $mat) {
        $data = [
            'seller_id'         => $seller, 
            'category_id'       => $cat,
            'title'             => $title, 
            'alias'             => url_title($title, '-', true),
            'short_description' => "A unique piece: $title.", 
            'long_description'  => "Technical details and history for the rug $title. Superior quality guaranteed.",
            'price'             => $price, 
            'stock_available'   => $stock,
            'dimensions'        => rand(150, 300) . 'x' . rand(200, 400), 
            'material'          => $mat,
            'product_status'    => $status,
            'created_at'        => date('Y-m-d H:i:s', strtotime('-' . rand(1, 100) . ' days'))
        ];
        $this->db->table('products')->insert($data);
        $id = $this->db->insertID();
        $this->db->table('product_photos')->insert(['product_id' => $id, 'file_name' => 'default.jpg', 'display_order' => 1]);
        return $id;
    }

    private function createOrder($client, $prod, $qty, $price, $dateStr, $status) {
        $dateSQL = date('Y-m-d H:i:s', strtotime($dateStr));
        $total = $qty * $price;
        $this->db->table('orders')->insert([
            'customer_id'          => $client, 
            'reference'            => 'CMD-' . strtoupper(bin2hex(random_bytes(4))),
            'order_date'           => $dateSQL, 
            'status'               => $status, 
            'total_ttc'            => $total, 
            'shipping_fees'        => 15.00,
            'delivery_method'      => 'Express Carrier', 
            'delivery_street'      => rand(1, 150) . ' Republic Street',
            'delivery_postal_code' => str_pad(rand(1000, 95000), 5, '0', STR_PAD_LEFT), 
            'delivery_city'        => 'Test-City', 
            'delivery_country'     => 'France'
        ]);
        $idCmd = $this->db->insertID();
        $this->db->table('order_items')->insert([
            'order_id'      => $idCmd, 
            'product_id'    => $prod,
            'quantity'      => $qty, 
            'unit_price'    => $price
        ]);
    }
}