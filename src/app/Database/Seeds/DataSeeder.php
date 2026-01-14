<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run()
    {
        helper('url');
        helper('text');

        // Nettoyage des tables
        $this->db->disableForeignKeyChecks();
        $tables = ['reviews', 'order_items', 'orders', 'cart_items', 'carts', 'product_photos', 'products', 'categories', 'addresses', 'sellers', 'customers', 'administrators', 'users'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->truncate();
            }
        }
        $this->db->enableForeignKeyChecks();

        echo "Creating Admins...\n";
        // 1. ADMINS
        $adminIds = [];
        $adminIds[] = $this->createUser('admin@tapis.com', 'System', 'Admin', 'ADMIN');
        $adminIds[] = $this->createUser('staff@tapis.com', 'Moderator', 'Marc', 'ADMIN');
        foreach ($adminIds as $id) {
            $this->db->table('administrators')->insert(['user_id' => $id]);
        }

        echo "Creating Sellers...\n";
        // 2. SELLERS
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
            $sellerStatus = (rand(1, 10) > 2) ? 'VALIDATED' : 'PENDING_VALIDATION'; 
            $this->db->table('sellers')->insert([
                'user_id'          => $vid,
                'shop_name'        => $b['name'],
                'siret'            => '123456789' . str_pad($idx, 5, '0', STR_PAD_LEFT),
                'status'           => $sellerStatus,
                'shop_description' => $b['desc']
            ]);
        }

        echo "Creating Clients...\n";
        // 3. CLIENTS
        $clientIds = [];
        for ($i = 0; $i < 30; $i++) {
            $cid = $this->createUser("client$i@mail.com", "ClientName$i", "ClientSurname$i", "CUSTOMER");
            $clientIds[] = $cid;
            $this->db->table('customers')->insert([
                'user_id'   => $cid,
                'phone'     => '06' . str_pad($i, 8, '0', STR_PAD_LEFT)
            ]);
        }

        // --- AJOUT : ADRESSES ---
        echo "Creating Addresses...\n";
        $cities = ['Paris', 'Lyon', 'Marseille', 'Bordeaux', 'Lille', 'Nice', 'Toulouse', 'Nantes'];
        $streets = ['Rue de la Paix', 'Avenue Victor Hugo', 'Boulevard Haussmann', 'Rue du Commerce', 'Avenue des Champs-Élysées'];

        // Pour chaque client, on crée 1 ou 2 adresses
        foreach ($clientIds as $cid) {
            $nbAddr = rand(1, 2);
            for ($k = 0; $k < $nbAddr; $k++) {
                $this->db->table('addresses')->insert([
                    'user_id'       => $cid,
                    'number'        => rand(1, 150),
                    'street'        => $streets[array_rand($streets)],
                    'postal_code'   => str_pad(rand(1000, 95000), 5, '0', STR_PAD_LEFT),
                    'city'          => $cities[array_rand($cities)],
                    'country'       => 'France',
                    'contact_phone' => '06' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT)
                ]);
            }
        }
        // Quelques adresses pour les vendeurs aussi
        foreach ($sellerIds as $sid) {
            $this->db->table('addresses')->insert([
                'user_id'       => $sid,
                'number'        => rand(1, 50),
                'street'        => 'Zone Industrielle Nord',
                'postal_code'   => str_pad(rand(1000, 95000), 5, '0', STR_PAD_LEFT),
                'city'          => $cities[array_rand($cities)],
                'country'       => 'France',
                'contact_phone' => '04' . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT)
            ]);
        }

        echo "Creating Categories...\n";
        // 4. CATEGORIES
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

        echo "Creating Products...\n";
        // 5. PRODUCTS
        $productIds = [];
        $rug_prefixes = ['Royal', 'Antique', 'Minimalist', 'Boho', 'Abstract', 'Classic', 'Imperial', 'Ethnic'];
        $rug_suffixes = ['Tapestry', 'Wool Rug', 'Hand-woven', 'Silk Piece', 'Carpet', 'Flatweave'];
        $materials = ['Cotton', 'Wool', 'Polyester', 'Polypropylene', 'Jute', 'Wool and cotton', 'Viscose'];

        $realImages = [
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQB8W68WPsCFzTSaQH14QUVxUCHYlAhGVqauA&s',
            'https://m.media-amazon.com/images/I/91f01GjXO3L._AC_UF1000,1000_QL80_.jpg',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXmV7v8KvilVT2uR0zrL4zAwhg-d-iwG9fow&s',
            'https://www.souffleinterieur.com/cdn/shop/files/tapis-salon-bleu-rectangulaire-salgueiro-rugs-souffle-d-interieur-2.jpg?v=1767337227&width=1214',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJOXsfQFZgcQ73N1Csmljxklqa9eyse1JLXg&s',
            'https://cdn.media.unamourdetapis.com/media/catalog/product/t/a/tapis-abisko1-beige-amb-unamourdetapis.jpg',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkwuXq14lz9QXV36kT8H0nRuoF_8n_3cVoAQ&s',
            'https://media.rueducommerce.fr/mktp/product/productImage/3/135/770707c0ac0d410aa2500a8aa3b410b0.webp',
        ];

        foreach ($sellerIds as $vid) {
            for ($p = 0; $p < 8; $p++) {
                $name = $rug_prefixes[array_rand($rug_prefixes)] . ' ' . $rug_suffixes[array_rand($rug_suffixes)] . ' ' . substr(uniqid(), -4);
                $price = rand(80, 2500);
                $stock = rand(0, 20);
                $cat = $catIds[array_rand($catIds)];
                $status = (rand(0, 10) > 1) ? 'APPROVED' : 'PENDING_VALIDATION';
                $mat = $materials[array_rand($materials)];

                $pid = $this->createProduct($vid, $cat, $name, $price, $stock, $status, $mat);
                $productIds[] = $pid;

                $imgUrl = $realImages[array_rand($realImages)];
                $this->db->table('product_photos')->insert([
                    'product_id' => $pid,
                    'file_name' => $imgUrl,
                    'display_order' => 1
                ]);
            }
        }

        // --- AJOUT : PANIERS (CARTS) ---
        echo "Creating Carts...\n";
        // On crée des paniers pour 10 clients aléatoires
        shuffle($clientIds);
        $cartClients = array_slice($clientIds, 0, 10);

        foreach ($cartClients as $cid) {
            $this->db->table('carts')->insert([
                'customer_id' => $cid,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
                'total'       => 0 // Sera mis à jour après l'ajout des items
            ]);
            $cartId = $this->db->insertID();

            $cartTotal = 0;
            $nbItems = rand(1, 4);
            
            // Choix de produits aléatoires
            $randomKeys = array_rand($productIds, $nbItems);
            if (!is_array($randomKeys)) $randomKeys = [$randomKeys];

            foreach ($randomKeys as $key) {
                $pId = $productIds[$key];
                $qty = rand(1, 3);

                // Récupération du prix
                $row = $this->db->table('products')->select('price')->where('id', $pId)->get()->getRow();
                $price = $row->price;

                $this->db->table('cart_items')->insert([
                    'cart_id'    => $cartId,
                    'product_id' => $pId,
                    'quantity'   => $qty
                ]);
                $cartTotal += $price * $qty;
            }

            // Mise à jour du total du panier
            $this->db->table('carts')->where('id', $cartId)->update(['total' => $cartTotal]);
        }

        echo "Creating Orders & Reviews...\n";

        $statuses = ['PENDING_VALIDATION', 'PAID', 'PREPARING', 'SHIPPED', 'DELIVERED', 'CANCELLED'];

        // Commentaires possibles
        $reviewsPool = [
            5 => ["Absolument magnifique !", "Qualité incroyable, je recommande.", "Parfait pour mon salon."],
            4 => ["Très beau tapis, un peu plus foncé que sur la photo.", "Bonne qualité mais livraison lente.", "Satisfait de mon achat."],
            3 => ["Correct pour le prix.", "Assez fin mais joli.", "Moyen."],
            2 => ["Déçu par la matière.", "Ne correspond pas à mes attentes."],
            1 => ["Mauvaise qualité, à éviter.", "Très déçu."]
        ];

        foreach ($clientIds as $cid) {
            $nbOrders = rand(1, 5);

            for ($c = 0; $c < $nbOrders; $c++) {
                $pId = $productIds[array_rand($productIds)];
                $qty = rand(1, 2);

                $prodQuery = $this->db->table('products')->select('price')->where('id', $pId)->get()->getRow();
                $price = $prodQuery->price;

                $randStat = rand(1, 100);
                if ($randStat < 10) $status = 'PENDING_VALIDATION';
                elseif ($randStat < 30) $status = 'PAID';
                elseif ($randStat < 50) $status = 'PREPARING';
                elseif ($randStat < 70) $status = 'SHIPPED';
                elseif ($randStat < 95) $status = 'DELIVERED';
                else $status = 'CANCELLED';

                $daysAgo = ($status === 'DELIVERED') ? rand(10, 100) : rand(0, 10);
                $dateStr = "-$daysAgo days";

                $orderId = $this->createOrder($cid, $pId, $qty, $price, $dateStr, $status);

                if ($status === 'DELIVERED' && rand(0, 1) === 1) {
                    $rating = rand(1, 5);
                    $comment = $reviewsPool[$rating][array_rand($reviewsPool[$rating])];

                    $reviewDate = date('Y-m-d H:i:s', strtotime($dateStr . " + " . rand(2, 5) . " days"));

                    $this->db->table('reviews')->insert([
                        'customer_id'       => $cid,
                        'product_id'        => $pId,
                        'rating'            => $rating,
                        'comment'           => $comment,
                        'moderation_status' => (rand(0, 10) > 1) ? 'PUBLISHED' : 'REFUSED',
                        'published_at'      => $reviewDate
                    ]);
                }
            }
        }

        echo "Seeding Complete.\n";
    }

    private function createUser($email, $lastname, $firstname, $role)
    {
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

    private function createProduct($seller, $cat, $title, $price, $stock, $status, $mat)
    {
        $data = [
            'seller_id'         => $seller,
            'category_id'       => $cat,
            'title'             => $title,
            'alias'             => url_title($title, '-', true),
            'short_description' => "A unique piece: $title.", 
            'long_description'  => "This $mat rug brings warmth and style to any interior. Hand-selected for its quality and unique pattern.",
            'price'             => $price,
            'stock_available'   => $stock,
            'dimensions'        => rand(150, 300) . 'x' . rand(200, 400),
            'material'          => $mat,
            'product_status'    => $status,
            'created_at'        => date('Y-m-d H:i:s', strtotime('-' . rand(1, 100) . ' days'))
        ];
        $this->db->table('products')->insert($data);
        return $this->db->insertID();
    }

    private function createOrder($client, $prod, $qty, $price, $dateStr, $status)
    {
        $dateSQL = date('Y-m-d H:i:s', strtotime($dateStr));
        $total = $qty * $price;
        $this->db->table('orders')->insert([
            'customer_id'          => $client,
            'reference'            => 'CMD-' . strtoupper(bin2hex(random_bytes(4))),
            'order_date'           => $dateSQL,
            'status'               => $status,
            'total_ttc'            => $total + 15.00,
            'shipping_fees'        => 15.00,
            'delivery_method'      => 'Express Carrier',
            'delivery_street'      => rand(1, 150) . ' Republic Street',
            'delivery_postal_code' => str_pad(rand(1000, 95000), 5, '0', STR_PAD_LEFT),
            'delivery_city'        => 'Paris',
            'delivery_country'     => 'France'
        ]);
        $idCmd = $this->db->insertID();
        $this->db->table('order_items')->insert([
            'order_id'      => $idCmd,
            'product_id'    => $prod,
            'quantity'      => $qty,
            'unit_price'    => $price
        ]);
        return $idCmd;
    }
}