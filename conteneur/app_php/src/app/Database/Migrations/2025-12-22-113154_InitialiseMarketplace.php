<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitialiseMarketplace extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        // USERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 255],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'lastname' => ['type' => 'VARCHAR', 'constraint' => 100],
            'firstname' => ['type' => 'VARCHAR', 'constraint' => 100],
            'created_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'role' => ['type' => 'ENUM', 'constraint' => ['ADMIN', 'SELLER', 'CUSTOMER'], 'default' => 'CUSTOMER'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);

        // CUSTOMERS
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'birth_date' => ['type' => 'DATE', 'null' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('customers', true);

        // SELLERS
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'shop_name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'shop_description' => ['type' => 'TEXT', 'null' => true],
            'siret' => ['type' => 'CHAR', 'constraint' => 14],
            'status' => ['type' => 'ENUM', 'constraint' => ['PENDING_VALIDATION', 'VALIDATED', 'REFUSED', 'SUSPENDED'], 'default' => 'PENDING_VALIDATION'],
            'refusal_reason' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addKey('status');
        $this->forge->addUniqueKey('siret');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('sellers', true);

        // ADMINISTRATORS
        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('administrators', true);

        // ADDRESSES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'number' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'street' => ['type' => 'VARCHAR', 'constraint' => 255],
            'postal_code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'city' => ['type' => 'VARCHAR', 'constraint' => 100],
            'country' => ['type' => 'VARCHAR', 'constraint' => 100],
            'contact_phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('addresses', true);

        // CATEGORIES
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 100],
            'alias' => ['type' => 'VARCHAR', 'constraint' => 120],
            'description' => ['type' => 'TEXT', 'null' => true],
            'image_url' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('alias');
        $this->forge->createTable('categories', true);

        // PRODUCTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'seller_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'category_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'alias' => ['type' => 'VARCHAR', 'constraint' => 150],
            'short_description' => ['type' => 'VARCHAR', 'constraint' => 255],
            'long_description' => ['type' => 'TEXT'],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true],
            'stock_available' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dimensions' => ['type' => 'VARCHAR', 'constraint' => 50],
            'material' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'product_status' => ['type' => 'ENUM', 'constraint' => ['PENDING_VALIDATION', 'APPROVED', 'REFUSED', 'OFFLINE', 'UNAVAILABLE'], 'default' => 'PENDING_VALIDATION'],
            'created_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'refusal_reason' => ['type' => 'TEXT', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['product_status', 'created_at']);
        $this->forge->addKey('title');
        $this->forge->addKey('price');
        $this->forge->addUniqueKey('alias');
        $this->forge->addForeignKey('seller_id', 'sellers', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('category_id', 'categories', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('products', true);

        // PRODUCT_PHOTOS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'file_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'display_order' => ['type' => 'INT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['product_id', 'display_order']);
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('product_photos', true);

        // CARTS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'unique' => true],
            'created_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')],
            'total' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 0.00, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('customer_id', 'customers', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('carts', true);

        // CART_ITEMS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'cart_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['cart_id', 'product_id']);
        $this->forge->addForeignKey('cart_id', 'carts', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cart_items', true);

        // ORDERS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'reference' => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'order_date' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'status' => ['type' => 'ENUM', 'constraint' => ['PENDING_VALIDATION', 'PAID', 'PREPARING', 'SHIPPED', 'DELIVERED', 'CANCELLED'], 'default' => 'PENDING_VALIDATION'],
            'delivery_method' => ['type' => 'VARCHAR', 'constraint' => 100],
            'delivery_street' => ['type' => 'VARCHAR', 'constraint' => 255],
            'delivery_postal_code' => ['type' => 'VARCHAR', 'constraint' => 10],
            'delivery_city' => ['type' => 'VARCHAR', 'constraint' => 100],
            'delivery_country' => ['type' => 'VARCHAR', 'constraint' => 100],
            'total_ttc' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true],
            'shipping_fees' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('customer_id', 'customers', 'user_id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('orders', true);

        // ORDER_ITEMS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'order_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'quantity' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'unit_price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['order_id', 'product_id']);
        $this->forge->addForeignKey('order_id', 'orders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('order_items', true);

        // REVIEWS
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'product_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rating' => ['type' => 'TINYINT', 'constraint' => 1, 'unsigned' => true],
            'comment' => ['type' => 'TEXT', 'null' => true],
            'published_at' => ['type' => 'DATETIME', 'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')],
            'moderation_status' => ['type' => 'ENUM', 'constraint' => ['PUBLISHED', 'REFUSED'], 'default' => 'PUBLISHED'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id');
        $this->forge->addForeignKey('customer_id', 'customers', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addUniqueKey(['customer_id', 'product_id']);
        $this->forge->createTable('reviews', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $tables = ['reviews', 'order_items', 'orders', 'cart_items', 'carts', 'product_photos', 'products', 'categories', 'addresses', 'administrators', 'sellers', 'customers', 'users'];
        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
        $this->db->enableForeignKeyChecks();
    }
}
