<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DataSeeder extends Seeder
{
    public function run()
    {
        helper('url');
        helper('text');
        helper('filesystem');

        // --- 1. Nettoyage de la BDD ---
        $this->db->disableForeignKeyChecks();
        $tables = ['reviews', 'order_items', 'orders', 'cart_items', 'carts', 'product_photos', 'products', 'categories', 'addresses', 'sellers', 'customers', 'administrators', 'users'];
        foreach ($tables as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->truncate();
            }
        }
        $this->db->enableForeignKeyChecks();

        // --- 2. Nettoyage du dossier des produits (public/uploads/products/) ---
        $this->cleanUploadsDirectory();

        // --- 3. Repérage des images sources ---
        // Vous devez créer ce dossier et y mettre vos images !
        $seedSourceDir = WRITEPATH . 'seed_images/'; 
        $availableImages = [];

        if (is_dir($seedSourceDir)) {
            $files = scandir($seedSourceDir);
            foreach ($files as $f) {
                if ($f === '.' || $f === '..') continue;
                // On accepte jpg, jpeg, png, webp
                if (preg_match('/\.(jpg|jpeg|png|webp)$/i', $f)) {
                    $availableImages[] = $f;
                }
            }
        }

        if (empty($availableImages)) {
            echo "\n/!\\ ATTENTION : Le dossier $seedSourceDir est vide ou n'existe pas.\n";
            echo "Veuillez créer le dossier 'writable/seed_images/' et y déposer des images.\n";
            echo "Les produits seront créés sans image pour l'instant.\n\n";
        } else {
            echo "Images trouvées dans le dossier source : " . count($availableImages) . "\n";
        }

        // --- Création des Utilisateurs ---
        echo "Creating Admins...\n";
        $adminIds = [];
        $adminIds[] = $this->createUser('admin@tapis.com', 'System', 'Admin', 'ADMIN');
        $adminIds[] = $this->createUser('staff@tapis.com', 'Moderator', 'Marc', 'ADMIN');
        foreach ($adminIds as $id) {
            $this->db->table('administrators')->insert(['user_id' => $id]);
        }

        echo "Creating Sellers...\n";
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
                'user_id' => $vid,
                'shop_name' => $b['name'],
                'siret' => '123456789' . str_pad($idx, 5, '0', STR_PAD_LEFT),
                'status' => $sellerStatus,
                'shop_description' => $b['desc']
            ]);
        }

        echo "Creating Clients...\n";
        $clientIds = [];
        for ($i = 0; $i < 30; $i++) {
            $cid = $this->createUser("client$i@mail.com", "ClientName$i", "ClientSurname$i", "CUSTOMER");
            $clientIds[] = $cid;
            $this->db->table('customers')->insert([
                'user_id' => $cid,
                'phone' => '06' . str_pad($i, 8, '0', STR_PAD_LEFT)
            ]);
        }

        // Adresses
        echo "Creating Addresses...\n";
        $cities = [['Paris', '75000'], ['Lyon', '69000'], ['Marseille', '13000'], ['Bordeaux', '33000']];
        $streets = ['Rue de la Paix', 'Av. Victor Hugo', 'Bd Haussmann', 'Rue du Commerce'];

        foreach ($clientIds as $cid) {
            $nbAddr = rand(1, 2);
            for ($k = 0; $k < $nbAddr; $k++) {
                $cityInfo = $cities[array_rand($cities)];
                $this->db->table('addresses')->insert([
                    'user_id'       => $cid,
                    'number'        => rand(1, 150),
                    'street'        => $streets[array_rand($streets)],
                    'postal_code'   => $cityInfo[1],
                    'city'          => $cityInfo[0],
                    'country'       => 'France',
                    'contact_phone' => '06' . str_pad(rand(0, 999999), 8, '0', STR_PAD_LEFT)
                ]);
            }
        }
        // Adresses vendeurs
        foreach ($sellerIds as $sid) {
            $cityInfo = $cities[array_rand($cities)];
            $this->db->table('addresses')->insert([
                'user_id'       => $sid,
                'number'        => rand(1, 50),
                'street'        => 'Zone Industrielle Nord',
                'postal_code'   => $cityInfo[1],
                'city'          => $cityInfo[0],
                'country'       => 'France',
                'contact_phone' => '04' . str_pad(rand(0, 999999), 8, '0', STR_PAD_LEFT)
            ]);
        }

        echo "Creating Categories...\n";
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
        $productIds = [];
        $rug_prefixes = ['Royal', 'Antique', 'Minimalist', 'Boho', 'Abstract', 'Classic', 'Imperial', 'Ethnic'];
        $rug_suffixes = ['Tapestry', 'Wool Rug', 'Hand-woven', 'Silk Piece', 'Carpet', 'Flatweave'];
        $materials = ['Cotton', 'Wool', 'Polyester', 'Polypropylene', 'Jute', 'Wool and cotton', 'Viscose'];

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

                // --- GESTION DES IMAGES LOCALES ---
                if (!empty($availableImages)) {
                    // On attribue 3 images aléatoires par produit
                    for ($i = 1; $i <= 3; $i++) {
                        $randomImageName = $availableImages[array_rand($availableImages)];
                        $sourcePath = $seedSourceDir . $randomImageName;
                        
                        // Copie du fichier source vers le dossier produit public
                        $newFileName = $this->copyLocalImage($pid, $sourcePath);

                        if ($newFileName) {
                            $this->db->table('product_photos')->insert([
                                'product_id'    => $pid,
                                'file_name'     => $newFileName, 
                                'display_order' => $i
                            ]);
                        }
                    }
                }
            }
        }

        echo "Creating Carts...\n";
        $cartClients = $clientIds;
        shuffle($cartClients);
        $cartClients = array_slice($cartClients, 0, 10);

        foreach ($cartClients as $cid) {
            $this->db->table('carts')->insert([
                'customer_id' => $cid,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
                'total'       => 0
            ]);
            $cartId = $this->db->insertID();

            $cartTotal = 0;
            $nbItems = rand(1, 3);
            
            // Choix de produits aléatoires
            $randomKeys = array_rand($productIds, $nbItems);
            if (!is_array($randomKeys))
                $randomKeys = [$randomKeys];

            foreach ($randomKeys as $key) {
                $pId = $productIds[$key];
                $qty = rand(1, 3);
                $row = $this->db->table('products')->select('price')->where('id', $pId)->get()->getRow();
                $price = $row->price;
                
                // Eviter doublons dans panier
                if ($this->db->table('cart_items')->where(['cart_id' => $cartId, 'product_id' => $pId])->countAllResults() == 0) {
                    $this->db->table('cart_items')->insert([
                        'cart_id'    => $cartId,
                        'product_id' => $pId,
                        'quantity'   => $qty
                    ]);
                    $cartTotal += $price * $qty;
                }
            }
            $this->db->table('carts')->where('id', $cartId)->update(['total' => $cartTotal]);
        }

        echo "Creating Orders & Reviews...\n";
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
                if ($randStat < 10)
                    $status = 'PENDING_VALIDATION';
                elseif ($randStat < 30)
                    $status = 'PAID';
                elseif ($randStat < 50)
                    $status = 'PREPARING';
                elseif ($randStat < 70)
                    $status = 'SHIPPED';
                elseif ($randStat < 95)
                    $status = 'DELIVERED';
                else
                    $status = 'CANCELLED';

                $daysAgo = ($status === 'DELIVERED') ? rand(10, 100) : rand(0, 10);
                $dateStr = "-$daysAgo days";

                $this->createOrder($cid, $pId, $qty, $price, $dateStr, $status);

                if ($status === 'DELIVERED' && rand(0, 1) === 1) {
                    $rating = rand(1, 5);
                    $comment = $reviewsPool[$rating][array_rand($reviewsPool[$rating])];
                    $reviewDate = date('Y-m-d H:i:s', strtotime($dateStr . " + " . rand(2, 5) . " days"));

                    $this->db->table('reviews')->insert([
                        'customer_id' => $cid,
                        'product_id' => $pId,
                        'rating' => $rating,
                        'comment' => $comment,
                        'moderation_status' => (rand(0, 10) > 1) ? 'PUBLISHED' : 'REFUSED',
                        'published_at' => $reviewDate
                    ]);
                }
            }
        }
        
        echo "Seeding Complete.\n";
    }

    // --- Helpers ---

    // Copie l'image locale vers le dossier public du produit
    private function copyLocalImage($productId, $sourcePath)
    {
        // Destination : public/uploads/products/{id}/
        $productDir = FCPATH . 'uploads/products/' . $productId . '/';
        
        if (!is_dir($productDir)) {
            mkdir($productDir, 0777, true);
        }

        // On garde l'extension d'origine
        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION);
        // On génère un nom aléatoire pour éviter les conflits
        $newFileName = md5(uniqid(rand(), true)) . '.' . $extension;
        $destPath = $productDir . $newFileName;

        if (copy($sourcePath, $destPath)) {
            return $newFileName;
        }
        return null;
    }

    // Nettoie le dossier public/uploads/products/ avant de commencer
    private function cleanUploadsDirectory()
    {
        $dir = FCPATH . 'uploads/products/'; 
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            return;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                @rmdir($file->getRealPath());
            } else {
                @unlink($file->getRealPath());
            }
        }
    }

    private function createUser($email, $lastname, $firstname, $role) {
        $this->db->table('users')->insert([
            'email' => $email,
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'lastname' => $lastname,
            'firstname' => $firstname,
            'role' => $role,
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

    private function createOrder($client, $prod, $qty, $price, $dateStr, $status) {
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
