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
    protected $beforeUpdate = ['generateAlias'];

    protected $validationRules = [
        'title'            => 'required|min_length[3]|max_length[150]',
        'price'            => 'required|decimal|greater_than[0]',
        'stock_available'  => 'required|integer|greater_than_equal_to[0]',
        'category_id'      => 'required|integer',
    ];


    // Get pending products
    public function getPendingProductsPaginated(int $perPage = 5)
    {
        return $this->select('products.*, sellers.shop_name, product_photos.file_name as image')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('products.product_status', STATUS_PENDING)
                    ->orderBy('products.created_at', 'ASC')
                    ->paginate($perPage, 'pending');
    }

    // Get all products for admin
    public function getAllProductsPaginated(int $perPage = 10)
    {
        return $this->select('products.*, sellers.shop_name, product_photos.file_name as image')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->orderBy('products.created_at', 'DESC')
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
    public function getSellerProducts(int $sellerId, int $perPage = 10)
    {
        return $this->select('products.*, categories.name as category_name')
                    ->join('categories', 'categories.id = products.category_id')
                    ->where('seller_id', $sellerId)
                    ->orderBy('created_at', 'DESC')
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

    //Create alias from title
    protected function generateAlias(array $data)
    {
        if (isset($data['data']['title']) && empty($data['data']['alias'])) {
            $data['data']['alias'] = url_title($data['data']['title'], '-', true);
        }
        return $data;
    }

    //Count products pending validation
    public function countProductsPendingValidation()
    {
        return $this->where('product_status', STATUS_PENDING)->countAllResults();
    }
}