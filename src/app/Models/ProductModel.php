<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Product;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $returnType       = Product::class;

    protected $useSoftDeletes   = true; 
    protected $deletedField     = 'deleted_at';

    protected $allowedFields = [
        'seller_id',
        'category_id', 
        'title', 
        'alias',
        'short_description', 
        'long_description', 
        'price',
        'stock_available', 
        'dimensions', 
        'material',
        'product_status', 
        'created_at', 
        'refusal_reason',
        'deleted_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $beforeInsert = ['generateAlias'];
    protected $beforeUpdate = ['generateAlias', 'checkStatusReset'];
    protected $beforeDelete = ['cleanupDependencies']; // Ajout du hook de nettoyage

    protected $validationRules = [
        'title'            => 'required|min_length[3]|max_length[150]',
        'price'            => 'required|decimal|greater_than[0]',
        'stock_available'  => 'required|integer|greater_than_equal_to[0]',
        'category_id'      => 'required|integer',
    ];



    // Get pending products
    public function getPendingProductsPaginated(int $perPage = 5)
    {
        return $this->select('products.*, sellers.shop_name')
                    ->select('(SELECT file_name FROM product_photos WHERE product_photos.product_id = products.id ORDER BY display_order ASC LIMIT 1) as image')
                    ->select('(SELECT GROUP_CONCAT(file_name) FROM product_photos WHERE product_photos.product_id = products.id ORDER BY display_order ASC) as images')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->where('product_status', STATUS_PENDING)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage, 'pending');
    }

    // Get all products (admin)
    public function getAllProductsPaginated(int $perPage = 10)
    {
        return $this->select('products.*, sellers.shop_name, categories.name as category_name')
                    ->select('(SELECT file_name FROM product_photos WHERE product_photos.product_id = products.id ORDER BY display_order ASC LIMIT 1) as image')
                    ->select('(SELECT GROUP_CONCAT(file_name) FROM product_photos WHERE product_photos.product_id = products.id ORDER BY display_order ASC) as images')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->join('categories', 'categories.id = products.category_id')
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage, 'catalog');
    }


    // Count pending products
    public function countPendingProducts()
    {
        return $this->where('product_status', STATUS_PENDING)->countAllResults();
    }

    // Validate a product
    public function validateProduct(int $id)
    {
        return $this->update($id, ['product_status' => STATUS_APPROVED]);
    }

    // Reject a product
    public function rejectProduct(int $id, string $reason)
    {
        return $this->update($id, [
            'product_status' => STATUS_REFUSED,
            'refusal_reason' => $reason
        ]);
    }

    // Product page
    public function getByAlias(string $alias)
    {
        return $this->select('products.*, categories.name as category_name, sellers.shop_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->where('products.alias', $alias)
                    ->where('products.product_status', STATUS_APPROVED)
                    ->first();
    }

    // List by category
    public function getByCategory(int $categoryId, string $sort = 'recent', int $perPage = 12)
    {
       
        $sorts = [
            'price_asc'  => 'price ASC',
            'price_desc' => 'price DESC',
            'recent'    => 'created_at DESC'
        ];

        return $this->select('products.*, product_photos.file_name as image')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('product_status', STATUS_APPROVED)
                    ->where('category_id', $categoryId)
                    ->orderBy($sorts[$sort] ?? 'created_at DESC')
                    ->paginate($perPage);
    }

    // Search
    public function search(string $term, int $perPage = 12)
    {
        return $this->select('products.*, product_photos.file_name as image')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->groupStart() 
                        ->like('title', $term)
                        ->orLike('short_description', $term)
                    ->groupEnd()
                    ->where('product_status', STATUS_APPROVED)
                    ->paginate($perPage);
    }


    //list seller's products
    public function getSellerProducts(int $sellerId, int $perPage = 10, ?string $search = null, ?string $status = null)
    {
        $this->select('products.*, categories.name as category_name')
             ->select('(SELECT file_name FROM product_photos WHERE product_photos.product_id = products.id ORDER BY display_order ASC LIMIT 1) as image')
             ->join('categories', 'categories.id = products.category_id')
             ->where('seller_id', $sellerId);

        if (!empty($search)) {
            $this->groupStart()
                 ->like('products.title', $search)
                 ->orLike('products.short_description', $search)
                 ->orLike('products.alias', $search)
                 ->groupEnd();
        }

        if (!empty($status)) {
            $this->where('products.product_status', $status);
        }

        return $this->orderBy('products.created_at', 'DESC')
                    ->paginate($perPage);
    }


    public function decrementStock(int $productId, int $quantity)
    {
        return $this->where('id', $productId)
                    ->decrement('stock_available', $quantity);
    }

    public function incrementStock(int $productId, int $quantity)
    {
        return $this->where('id', $productId)
                    ->increment('stock_available', $quantity);
    }

    public function checkSufficientStock(int $productId, int $requestedQuantity): bool
    {
        $product = $this->select('stock_available')
                        ->where('id', $productId)
                        ->first();

        if (!$product) {
            return false; 
        }

        return $product->stock_available >= $requestedQuantity;
    }

   

    //for admin, products pending validation
    public function getProductsPendingValidation()
    {
        return $this->select('products.id, products.title, products.price, products.created_at, sellers.shop_name')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->where('products.product_status', STATUS_PENDING)
                    ->findAll();
    }

    public function getAllWithImage(int $limit = 6)
    {
        return $this->select('products.*, product_photos.file_name as image')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('products.product_status', STATUS_APPROVED)
                    ->orderBy('products.created_at', 'DESC')
                    ->findAll($limit); 
    }
    
    public function getAllWithSeller(int $perPage = 15)
    {
        return $this->select('products.*, sellers.shop_name')
                    ->join('sellers', 'sellers.user_id = products.seller_id') 
                    ->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
    }

    // Count low stock products for a seller
    public function countSellerLowStock(int $sellerId, int $threshold)
    {
        return $this->where('seller_id', $sellerId)
                    ->where('stock_available <', $threshold)
                    ->countAllResults();
    }

    //Count products pending validation
    public function countProductsPendingValidation()
    {
        return $this->where('product_status', STATUS_PENDING)->countAllResults();
    }

    public function countSellerPendingProducts(int $sellerId): int
    {
        $status = defined('STATUS_PENDING') ? STATUS_PENDING : 1;
        return $this->where('seller_id', $sellerId)
                    ->where('product_status', $status)
                    ->countAllResults();
    }

    // Callbacks
    protected function generateAlias(array $data)
    {
        if (isset($data['data']['title'])) {
            $data['data']['alias'] = url_title($data['data']['title'], '-', true);
            
            // Unicité basique (à améliorer pour production)
            $existing = $this->where('alias', $data['data']['alias'])->first();
            if ($existing && (!isset($data['id']) || reset($data['id']) != $existing->id)) {
                $data['data']['alias'] .= '-' . uniqid();
            }
        }
        return $data;
    }

    protected function checkStatusReset(array $data)
    {
        if (!isset($data['id'])) return $data;

        // CodeIgniter update() passe un tableau d'ids, on prend le premier
        $ids = (array)$data['id'];
        $id = reset($ids); 

        // On récupère l'ancienne version du produit
        $oldProduct = $this->find($id);
        if (!$oldProduct) return $data;

        $hasChanged = false;
        
        // Liste des champs qui nécessitent une re-validation
        $sensitiveFields = [
            'title', 'category_id', 
            'short_description', 'long_description', 
            'dimensions', 'material'
        ];

        foreach ($data['data'] as $key => $value) {
            // On ignore les champs non sensibles ou absents
            if (!in_array($key, $sensitiveFields)) continue;

            // Comparaison stricte avec normalisation
            $oldVal = trim((string)$oldProduct->$key);
            $newVal = trim((string)$value);
            
            $oldVal = str_replace("\r\n", "\n", $oldVal);
            $newVal = str_replace("\r\n", "\n", $newVal);

            if ($oldVal !== $newVal) {
                $hasChanged = true;
                break; // Un seul changement suffit
            }
        }

        // Si modification sensible détectée sur un produit déjà validé ou refusé
        if ($hasChanged && $oldProduct->product_status !== STATUS_PENDING) {
            $data['data']['product_status'] = STATUS_PENDING;
            $data['data']['refusal_reason'] = null;
        }

        return $data;
    }

    protected function cleanupDependencies(array $data)
    {
        if (empty($data['id'])) return $data;

        $ids = (array)$data['id'];
        $db = \Config\Database::connect();

        // Suppression des avis liés
        $db->table('reviews')->whereIn('product_id', $ids)->delete();
        
        // Suppression des items de panier liés
        $db->table('cart_items')->whereIn('product_id', $ids)->delete();

        return $data;
    }
}