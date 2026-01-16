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

    protected $productModel;
    protected $categoryModel;
    protected $photoModel;
    protected $reviewModel;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->photoModel = new ProductPhotoModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {

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
        $dimBounds = $this->productModel->getDimensionBounds();

        $data = [
            'products' => $this->productModel->filterProducts($filters, 12),
            'pager' => $this->productModel->pager,
            'categories' => $this->categoryModel->findAll(),
            'materials' => $this->productModel->getUniqueMaterials(),
            'sellers' => $this->productModel->getActiveSellers(),
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

        $data = [
            'products' => $this->productModel->search($term ?? '', 12),
            'pager' => $this->productModel->pager,
            'searchTerm' => $term
        ];

        return view('pages/catalog', $data);
    }

    public function detail($alias = null)
    {
        if (!$alias) {
            return redirect()->to('pages/catalog');
        }

        $product = $this->productModel->getByAlias($alias);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound('Produit introuvable');
        }

        // Récupérer toutes les photos
        $photos = $this->photoModel->getPhotosByProduct($product->id);

        $reviews = $this->reviewModel->getReviewsForProduct($product->id);
        $reviewStats = $this->reviewModel->getProductStats($product->id);

        $similarProducts = $this->productModel->getSimilarProducts($product->category_id, $product->id);

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
