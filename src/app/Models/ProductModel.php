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

    // Méthode pour vérifier si le stock est suffisant
    public function hasSufficientStock(int $productId, int $quantity): bool
    {
        $product = $this->find($productId);
        return $product && $product->stock_available >= $quantity;
    }

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
        return $this->select('products.*, categories.name as category_name, sellers.shop_name, product_photos.file_name as image')
                    ->join('categories', 'categories.id = products.category_id')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
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

    /**
     * Get min and max dimensions (width)
     */
    public function getDimensionBounds()
    {
        // Optimization: Cache result for 1 hour
        if ($cached = cache('dim_bounds')) {
            return $cached;
        }

        $d1 = "CAST(SUBSTRING_INDEX(dimensions, 'x', 1) AS UNSIGNED)";
        $d2 = "CAST(SUBSTRING_INDEX(dimensions, 'x', -1) AS UNSIGNED)";
        
        $result = $this->select("MIN(LEAST($d1, $d2)) as min, MAX(GREATEST($d1, $d2)) as max")
                    ->where('product_status', STATUS_APPROVED)
                    ->where('dimensions !=', '')
                    ->like('dimensions', 'x')
                    ->first();
        
        cache()->save('dim_bounds', $result, 3600);
        return $result;
    }

    /**
     * Filter products for the catalog
     */
    public function filterProducts(array $filters = [], int $perPage = 12)
    {
        $builder = $this->select('products.*, product_photos.file_name as image, categories.name as category_name, sellers.shop_name as seller_name')
                        ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                        ->join('categories', 'categories.id = products.category_id')
                        ->join('sellers', 'sellers.user_id = products.seller_id')
                        ->where('products.product_status', STATUS_APPROVED);

        // Filter by search term
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('products.title', $filters['search'])
                    ->orLike('products.short_description', $filters['search'])
                    ->groupEnd();
        }

        // Filter by categories
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $builder->groupStart();
            foreach ($filters['categories'] as $cat) {
                $builder->orLike('categories.name', $cat);
            }
            $builder->groupEnd();
        }

        // Filter by materials
        if (!empty($filters['materials']) && is_array($filters['materials'])) {
            $builder->groupStart();
            foreach ($filters['materials'] as $mat) {
                $builder->orLike('products.material', $mat);
            }
            $builder->groupEnd();
        }
        
        // Filter by precise dimensions (Length/Width intervals cross-check)
        // Check if any dimension filter is set
        $w_min = isset($filters['width_min']) && $filters['width_min'] !== '' ? (int)$filters['width_min'] : null;
        $w_max = isset($filters['width_max']) && $filters['width_max'] !== '' ? (int)$filters['width_max'] : null;
        $l_min = isset($filters['length_min']) && $filters['length_min'] !== '' ? (int)$filters['length_min'] : null;
        $l_max = isset($filters['length_max']) && $filters['length_max'] !== '' ? (int)$filters['length_max'] : null;

        if ($w_min !== null || $w_max !== null || $l_min !== null || $l_max !== null) {
             // Defaults if not set (using extreme bounds to simulate "no limit" if one side is missing)
             $w_min = $w_min ?? 0;
             $w_max = $w_max ?? 99999;
             $l_min = $l_min ?? 0;
             $l_max = $l_max ?? 99999;

             // Extract parts logic
             $d1 = "CAST(SUBSTRING_INDEX(products.dimensions, 'x', 1) AS UNSIGNED)";
             $d2 = "CAST(SUBSTRING_INDEX(products.dimensions, 'x', -1) AS UNSIGNED)";

             // (D1 in [w_min, w_max] AND D2 in [l_min, l_max]) OR (D1 in [l_min, l_max] AND D2 in [w_min, w_max])
             $builder->groupStart()
                ->groupStart()
                    ->where("$d1 >=", $w_min)->where("$d1 <=", $w_max)
                    ->where("$d2 >=", $l_min)->where("$d2 <=", $l_max)
                ->groupEnd()
                ->orGroupStart()
                    ->where("$d1 >=", $l_min)->where("$d1 <=", $l_max)
                    ->where("$d2 >=", $w_min)->where("$d2 <=", $w_max)
                ->groupEnd()
             ->groupEnd();
        }

        // Filter by sellers
        if (!empty($filters['sellers']) && is_array($filters['sellers'])) {
            $builder->groupStart();
            foreach ($filters['sellers'] as $seller) {
                $builder->orLike('sellers.shop_name', $seller);
            }
            $builder->groupEnd();
        }

        // Filter by price range
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $builder->where('products.price >=', $filters['price_min']);
        }
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $builder->where('products.price <=', $filters['price_max']);
        }

        // Sort
        $sort = $filters['sort'] ?? 'nouveautes';
        switch ($sort) {
            case 'prix_asc':
                $builder->orderBy('products.price', 'ASC');
                break;
            case 'prix_desc':
                $builder->orderBy('products.price', 'DESC');
                break;
            case 'nouveautes':
            default:
                $builder->orderBy('products.created_at', 'DESC');
                break;
        }

        return $builder->paginate($perPage);
    }

    /**
     * Get unique materials from approved products
     */
    public function getUniqueMaterials()
    {
        $query = $this->builder()
                      ->select('DISTINCT(material) as name')
                      ->where('product_status', STATUS_APPROVED)
                      ->where('material !=', '')
                      ->orderBy('material', 'ASC')
                      ->get();
                      
        return $query->getResult();
    }

    /**
     * Get active sellers relative to products
     */
    public function getActiveSellers() 
    {
        $query = $this->builder()
                    ->select('DISTINCT(sellers.shop_name) as name')
                    ->join('sellers', 'sellers.user_id = products.seller_id')
                    ->where('product_status', STATUS_APPROVED)
                    ->orderBy('sellers.shop_name', 'ASC')
                    ->get();
        return $query->getResult();
    }

    /**
     * Get similar products
     */
    public function getSimilarProducts(int $categoryId, int $excludeId, int $limit = 4)
    {
        return $this->select('products.*, product_photos.file_name as image')
                    ->join('product_photos', 'product_photos.product_id = products.id AND product_photos.display_order = 1', 'left')
                    ->where('category_id', $categoryId)
                    ->where('products.id !=', $excludeId)
                    ->where('products.product_status', STATUS_APPROVED)
                    ->orderBy('RAND()')
                    ->limit($limit)
                    ->find();
    }
}