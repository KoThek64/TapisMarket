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

    // Retrieves reviews for display
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

    public function getProductStats(int $productId)
    {
        return $this->select('AVG(rating) as average_rating, COUNT(id) as count') 
                    ->where('product_id', $productId)
                    ->where('moderation_status', 'PUBLISHED')
                    ->first();
    }

    // Checks if a customer has already rated a product
    public function hasAlreadyRated(int $productId, int $customerId): bool
    {
        return $this->where('product_id', $productId)
                    ->where('customer_id', $customerId)
                    ->countAllResults() > 0;
    }

    // Allows moderating a review
    public function moderateReview(int $reviewId, string $status)
    {
        return $this->update($reviewId, ['moderation_status' => $status]);
    }

    // Checks if a customer has bought and received a product before being able to rate it
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

    // Base query construction for reviews
    private function _getReviewBuilder()
    {
        return $this->select('reviews.*, products.title as product_name, products.id as product_id, users.lastname, users.firstname, users.email')
                    ->join('products', 'products.id = reviews.product_id')
                    ->join('customers', 'customers.user_id = reviews.customer_id')
                    ->join('users', 'users.id = customers.user_id');
    }

    // Returns all reviews with pagination
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
}