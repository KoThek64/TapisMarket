<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ProductPhotoModel;
use App\Models\ReviewModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Catalog extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        // Récupération des filtres
        $filters = [
            'search' => $this->request->getGet('search'),
            'categories' => $this->request->getGet('cat'),
            'materials' => $this->request->getGet('mat'),
            'width_min' => $this->request->getGet('width_min'),
            'width_max' => $this->request->getGet('width_max'),
            'length_min' => $this->request->getGet('length_min'),
            'length_max' => $this->request->getGet('length_max'),
            'sellers' => $this->request->getGet('seller'),
            'price_min' => $this->request->getGet('price_min'),
            'price_max' => $this->request->getGet('price_max'),
            'sort' => $this->request->getGet('sort')
        ];

        // Bounds for sliders
        $dimBounds = $productModel->getDimensionBounds();

        $data = [
            'products' => $productModel->filterProducts($filters, 12),
            'pager' => $productModel->pager,
            'categories' => $categoryModel->findAll(),
            'materials' => $productModel->getUniqueMaterials(),
            'sellers' => $productModel->getActiveSellers(),
            'searchTerm' => $filters['search'],
            'activeSection' => $this->request->getGet('active_section'),

            // Filters data
            'selectedCategories' => $filters['categories'] ?? [],
            'selectedMaterials' => $filters['materials'] ?? [],
            'selectedSellers' => $filters['sellers'] ?? [],

            // Price range
            'selectedPriceMin' => $filters['price_min'] ?? 0,
            'selectedPriceMax' => $filters['price_max'] ?? 5000,

            // Dimensions range (default to query or bounds or fallback)
            'dimMinBound' => $dimBounds->min ?? 0,
            'dimMaxBound' => $dimBounds->max ?? 500,

            'selectedWidthMin' => $filters['width_min'] ?? ($dimBounds->min ?? 0),
            'selectedWidthMax' => $filters['width_max'] ?? ($dimBounds->max ?? 500),
            'selectedLengthMin' => $filters['length_min'] ?? ($dimBounds->min ?? 0),
            'selectedLengthMax' => $filters['length_max'] ?? ($dimBounds->max ?? 500),

            'selectedSort' => $filters['sort'] ?? 'nouveautes'
        ];

        return view('pages/catalog', $data);
    }

    public function search()
    {
        $term = $this->request->getGet('q');
        $productModel = new ProductModel();

        $data = [
            'products' => $productModel->search($term ?? '', 12),
            'pager' => $productModel->pager,
            'searchTerm' => $term
        ];

        return view('pages/catalog', $data);
    }

    public function detail($alias = null)
    {
        if (!$alias) {
            return redirect()->to('pages/catalog');
        }

        $productModel = new ProductModel();

        $product = $productModel->getByAlias($alias);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Produit introuvable');
        }

        // Récupérer toutes les photos
        $photoModel = new ProductPhotoModel();
        $photos = $photoModel->getPhotosByProduct($product->id);


        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getReviewsForProduct($product->id);
        $reviewStats = $reviewModel->getProductStats($product->id);

        $similarProducts = $productModel->getSimilarProducts($product->category_id, $product->id);

        $data = [
            'product' => $product,
            'photos' => $photos,
            'similarProducts' => $similarProducts,
            'reviews' => $reviews,
            'reviewStats' => $reviewStats
        ];

        return view('pages/product_sheet', $data);
    }
}
