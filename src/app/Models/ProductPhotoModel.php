<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ProductPhoto;

class ProductPhotoModel extends Model
{
    protected $table            = 'product_photos';
    protected $primaryKey       = 'photo_id';
    protected $returnType       = ProductPhoto::class;

    protected $allowedFields    = ['product_id', 'file_name', 'display_order'];



    // Retrieves all images for the product page
    public function getGallery(int $productId)
    {
        return $this->where('product_id', $productId)
                    ->orderBy('display_order', 'ASC')
                    ->findAll();
    }

    
    // Retrieves the cover image
    public function getMainImage(int $productId)
    {
        return $this->where('product_id', $productId)
                    ->orderBy('display_order', 'ASC') 
                    ->first();
    }

    
    // Sets a main image
    public function setMain(int $photoId, int $productId)
    {
        
        $this->where('product_id', $productId)
             ->set(['display_order' => 2])
             ->update();

        return $this->update($photoId, ['display_order' => 1]);
    }
    
    // Deletes all photos of a product
    public function deleteAll(int $productId)
    {
        return $this->where('product_id', $productId)->delete();
    }

    // Get all photos for a product sorted by display order
    public function getPhotosByProduct(int $productId)
    {
        return $this->where('product_id', $productId)
                    ->orderBy('display_order', 'ASC')
                    ->findAll();
    }
}