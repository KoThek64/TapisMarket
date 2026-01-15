<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\ProductPhoto;

class ProductPhotoModel extends Model
{
    protected $table = 'product_photos';
    protected $primaryKey = 'id';
    protected $returnType = ProductPhoto::class;

    protected $allowedFields = ['product_id', 'file_name', 'display_order'];

    // Règles de validation (pour les champs BDD)
    protected $validationRules = [
        'product_id' => 'required|integer',
        'file_name' => 'required|max_length[255]',
        'display_order' => 'integer'
    ];

    // règles spécifiques pour l'upload (utilisées par le controller)
    public function getUploadRules(): array
    {
        return [
            'photos' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[photos]',
                    'is_image[photos]',
                    'mime_in[photos,' . ALLOWED_IMAGE_TYPES . ']',
                    'max_size[photos,' . MAX_UPLOAD_SIZE . ']',
                ],
            ]
        ];
    }
    public function getGallery(int $productId)
    {
        return $this->where('product_id', $productId)
            ->orderBy('display_order', 'ASC')
            ->findAll();
    }


    // recupere l'image de couverture
    public function getMainImage(int $productId)
    {
        return $this->where('product_id', $productId)
            ->orderBy('display_order', 'ASC')
            ->first();
    }


    // definit une image principale
    public function setMain(int $photoId, int $productId)
    {

        $this->where('product_id', $productId)
            ->set(['display_order' => 2])
            ->update();

        return $this->update($photoId, ['display_order' => 1]);
    }

    // supprime toutes les photos d'un produit
    public function deleteAll(int $productId)
    {
        return $this->where('product_id', $productId)->delete();
    }

    // Calcule le prochain numéro d'ordre pour l'affichage
    public function getNextDisplayOrder(int $productId): int
    {
        $hasCover = $this->where('product_id', $productId)
            ->where('display_order', 1)
            ->countAllResults() > 0;
        if (!$hasCover)
            return 1;

        $maxOrder = $this->where('product_id', $productId)
            ->selectMax('display_order')
            ->first();
        return ($maxOrder->display_order ?? 0) + 1;
    }

    // Get all photos for a product sorted by display order
    public function getPhotosByProduct(int $productId)
    {
        return $this->where('product_id', $productId)
            ->orderBy('display_order', 'ASC')
            ->findAll();
    }
}
