<?php

namespace App\Traits;

trait ImageTrait
{
    // Manage product image
    public function getImageUrl(?string $filename, ?string $placeholderUrl = null): string
    {
        if ($placeholderUrl === null) {
            $placeholderUrl = DEFAULT_PRODUCT_IMAGE;
        }

        if (empty($filename) || $filename === 'default.jpg') {
            return $placeholderUrl;
        }

        if (strpos($filename, 'http') === 0) {
            return $filename;
        }

        return base_url('uploads/products/' . $this->id . '/' . $filename);
    }

    // Alias for getImageUrl to match Entity usage
    public function getUrlImage(?string $filename, ?string $placeholderUrl = null): string
    {
        return $this->getImageUrl($filename, $placeholderUrl);
    }
}
