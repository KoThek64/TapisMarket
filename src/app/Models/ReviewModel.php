<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Review;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $returnType       = Review::class;

    protected $allowedFields    = [
        'product_id',
        'customer_id',
        'rating',
        'comment',
        'published_at',
        'moderation_status' 
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'published_at';
    protected $updatedField  = '';

    protected $validationRules = [
        'product_id'        => 'required|integer',
        'customer_id'       => 'required|integer',
        'rating'            => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        'comment'           => 'required|min_length[5]|max_length[1000]',
        'moderation_status' => 'in_list[PUBLISHED,REFUSED]', 
    ];
    
    protected $validationMessages = [
        'rating' => [
            'less_than_equal_to' => 'The rating must be between 1 and 5.',
        ]
    ];

    //Recupere les stats global pour un vendeur (nombre d'avis et note moyenne)
    public function getSellerGlobalStats(int $sellerId): array
    {
        // On récupère le nombre TOTAL d'avis visible (y compris refusés) pour la cohérence avec la liste
        // Mais la note moyenne n'est calculée que sur les avis PUBLIÉS car c'est ce que voient les clients
        $result = $this->select("
                            COUNT(*) as total_count, 
                            AVG(CASE WHEN reviews.moderation_status = 'PUBLISHED' THEN reviews.rating ELSE NULL END) as avg_rating,
                            COUNT(CASE WHEN reviews.moderation_status = 'PUBLISHED' THEN 1 ELSE NULL END) as published_count
                       ")
                       ->join('products', 'products.id = reviews.product_id')
                       ->where('products.seller_id', $sellerId)
                       ->where('products.deleted_at', null) // Exclure produits supprimés
                       ->asArray()
                       ->first();

        return [
            'count'           => $result['total_count'] ?? 0, 
            'published_count' => $result['published_count'] ?? 0,
            'avg_rating'      => $result['avg_rating'] ?? 0
        ];
    }

    //Recupere les avis paginé pour un vendeur
    public function getSellerReviews(int $sellerId, int $perPage = 10, string $sort = 'date_desc')
    {
        $builder = $this->select('reviews.*, products.title as product_title, products.id as product_id, users.firstname, users.lastname')
                    ->select("(SELECT COUNT(DISTINCT o.id) 
                               FROM orders o 
                               JOIN order_items oi ON oi.order_id = o.id 
                               JOIN products p ON p.id = oi.product_id 
                               WHERE o.customer_id = reviews.customer_id 
                               AND p.seller_id = {$sellerId}
                               AND o.status != 'CANCELLED') as orders_count")
                    ->join('products', 'products.id = reviews.product_id')
                    ->join('customers', 'customers.user_id = reviews.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->where('products.seller_id', $sellerId)
                    ->where('products.deleted_at', null); // Exclure produits supprimés
        
        switch ($sort) {
            case 'date_asc':
                $builder->orderBy('reviews.published_at', 'ASC');
                break;
            case 'rating_desc':
                $builder->orderBy('reviews.rating', 'DESC');
                break;
            case 'rating_asc':
                $builder->orderBy('reviews.rating', 'ASC');
                break;
            case 'date_desc':
            default:
                $builder->orderBy('reviews.published_at', 'DESC');
                break;
        }

        return $builder->paginate($perPage);
    }

    // Recupere les avis pour l'affichage
    public function getReviewsForProduct(int $productId)
    {
        return $this->select('reviews.*, users.firstname, users.lastname')
                    ->join('customers', 'customers.user_id = reviews.customer_id')
                    ->join('users', 'users.id = customers.user_id')
                    ->where('reviews.product_id', $productId)
                    ->where('reviews.moderation_status', 'PUBLISHED') 
                    ->orderBy('reviews.published_at', 'DESC')
                    ->findAll();
    }

    // Note moyenne pour tous les produits d'un vendeur
    public function getSellerAverageRating(int $sellerId): array
    {
        $result = $this->select('AVG(reviews.rating) as average, COUNT(reviews.id) as count')
                       ->join('products', 'products.id = reviews.product_id')
                       ->where('products.seller_id', $sellerId)
                       ->where('products.deleted_at', null) // Exclure produits supprimés
                       ->where('reviews.moderation_status', 'PUBLISHED') 
                       ->first();

        return [
            'average' => $result->average ?? 0,
            'count' => $result->count ?? 0
        ];
    }

    public function getProductStats(int $productId)
    {
        return $this->select('AVG(rating) as average_rating, COUNT(id) as count') 
                    ->where('product_id', $productId)
                    ->where('moderation_status', 'PUBLISHED')
                    ->first();
    }

    public function countPublishedReviewsForUser(int $userId)
    {
        return $this->where('customer_id', $userId)
                    ->where('moderation_status', 'PUBLISHED')
                    ->countAllResults();
    }

    // Verifie si un client a deja note un produit
    public function hasAlreadyRated(int $productId, int $customerId): bool
    {
        return $this->where('product_id', $productId)
                    ->where('customer_id', $customerId)
                    ->countAllResults() > 0;
    }

    // Permet de moderer un avis
    public function moderateReview(int $reviewId, string $status)
    {
        return $this->update($reviewId, ['moderation_status' => $status]);
    }

    // Verifie si un client a achete et recu un produit avant de pouvoir le noter
    public function hasBoughtAndReceived(int $customerId, int $productId): bool
    {
        return $this->db->table('orders')
            ->join('order_items', 'orders.id = order_items.order_id')
            ->where('orders.customer_id', $customerId)
            ->where('order_items.product_id', $productId)
            ->where('orders.status', 'DELIVERED') 
            ->countAllResults() > 0;
    }

    public function countCriticalReviews() {
        return $this->where('rating <=', 2)->countAllResults();
    }

    // Construction de la requete de base pour les avis
    private function _getReviewBuilder()
    {
        return $this->select('reviews.*, products.title as product_name, products.id as product_id, users.lastname, users.firstname, users.email')
                    ->join('products', 'products.id = reviews.product_id')
                    ->join('customers', 'customers.user_id = reviews.customer_id')
                    ->join('users', 'users.id = customers.user_id');
    }

    // Retourne tous les avis avec pagination
    public function getAllReviews(int $perPage = 10)
    {
        return $this->_getReviewBuilder()
                    ->orderBy('reviews.published_at', 'DESC')
                    ->paginate($perPage);
    }

    // Renvoie les avis critiques (note <= 2) avec pagination
    public function getCriticalReviews(int $perPage = 10)
    {
        return $this->_getReviewBuilder()
                    ->where('reviews.rating <=', 2)
                    ->orderBy('reviews.rating', 'ASC')
                    ->orderBy('reviews.published_at', 'DESC')
                    ->paginate($perPage);
    }

    // Renvoie les avis refusés avec pagination
    public function getRejectedReviews(int $perPage = 10)
    {
        return $this->_getReviewBuilder()
                    ->where('reviews.moderation_status', 'REFUSED')
                    ->paginate($perPage);
    }

    // Renvoie les avis publiés avec pagination
    public function getPublishedReviews(int $perPage = 10)
    {
        return $this->_getReviewBuilder()
                    ->where('reviews.moderation_status', 'PUBLISHED')
                    ->paginate($perPage);
    }

    public function getReviewsByFilter(?string $filter, int $perPage = 10)
    {
        return match ($filter) {
            'critical' => $this->getCriticalReviews($perPage),
            'rejected' => $this->getRejectedReviews($perPage),
            'published' => $this->getPublishedReviews($perPage),
            default    => $this->getAllReviews($perPage),
        };
    }

    public function getPaginatedReviewsForUser(int $userId, int $perPage = 8): array
    {
        return $this->select('reviews.*, products.title as product_name, products.deleted_at as product_deleted_at')
                    ->join('products', 'products.id = reviews.product_id', 'left')
                    ->where('reviews.customer_id', $userId) 
                    ->orderBy('reviews.id', 'DESC') 
                    ->paginate($perPage);
    }

    public function getReviewForProductByUser(int $userId, int $productId)
    {
        return $this->where('customer_id', $userId)
                    ->where('product_id', $productId)
                    ->first();
    }
}