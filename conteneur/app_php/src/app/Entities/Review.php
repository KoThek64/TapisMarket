<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Traits\RatingTrait;

class Review extends Entity
{
    use RatingTrait;

    protected $dates = ['published_at'];

    protected $casts = [
        'rating' => 'integer',
        'product_id' => 'integer',
        'customer_id' => 'integer'
    ];

    // Returns an HTML star representation of the rating
    public function getStars(): string
    {
        return $this->generateStarHtml($this->rating);
    }

    // Returns an excerpt of the comment
    public function getCommentExcerpt(int $length = 50): string
    {
        $text = $this->attributes['comment'] ?? '';

        if (mb_strlen($text) > $length) {
            return mb_substr($text, 0, $length) . '...';
        }
        return $text;
    }

    // Returns the publication date in relative format
    public function getRelativeDate(): string
    {
        return $this->published_at->humanize();
    }

    // Checks if the review is published, useful for admin
    public function isPublished(): bool
    {
        return $this->attributes['moderation_status'] === REVIEW_PUBLISHED;
    }

}
