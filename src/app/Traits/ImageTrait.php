<?php

namespace App\Traits;

trait ImageTrait
{
    // Manage product image
    public function getImageUrl(?string $filename, ?string $placeholderUrl = null): string
    {
        if ($placeholderUrl === null) {
            $placeholderUrl = 'https://images.unsplash.com/photo-1600166898405-da9535204843?q=80&w=400';
        }

        if (empty($filename)) {
            return $placeholderUrl;
        }

        if (strpos($filename, 'http') === 0) {
            return $filename;
        }
        
        return base_url('images/' . $filename);
    }

    // Alias for getImageUrl to match Entity usage
    public function getUrlImage(?string $filename, ?string $placeholderUrl = null): string
    {
        return $this->getImageUrl($filename, $placeholderUrl);
    }
}