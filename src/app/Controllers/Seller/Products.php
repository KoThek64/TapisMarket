<?php

namespace App\Controllers\Seller;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Exception;
use CodeIgniter\Database\Exceptions\DataException;

class Products extends SellerBaseController
{

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    // Vérifie que le produit appartient bien au vendeur connecté
    private function verifyProductOwnership(int $productId): ?object
    {
        $userId = $this->getSellerId();
        $product = $this->productModel->withDeleted()->find($productId);

        if (!$product || $product->seller_id != $userId) {
            return null;
        }

        return $product;
    }


    //page qui liste les produits
    public function index()
    {
        $userId = $this->getSellerId();
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');

        $seller = $this->sellerModel->find($userId);
        $isSellerValidated = $seller && $seller->isActive();

        $myProducts = $this->productModel->getSellerProducts($userId, 12, $search, $status);
        $pager = $this->productModel->pager;

        try {
            $totalProducts = $this->productModel->countSellerProducts($userId);
            $lowStockCount = $this->productModel->countSellerLowStock($userId, 5);
        } catch (Exception $e) {
            $totalProducts = 0;
            $lowStockCount = 0;
        }

        $data = array_merge($this->sellerData, [
            'myProducts' => $myProducts,
            'pager' => $pager,
            'isSellerValidated' => $isSellerValidated,
            'search' => $search,
            'status' => $status,
            'stats' => [
                'total' => $totalProducts,
                'lowStock' => $lowStockCount
            ],
            'title' => 'Mes Produits',
            'subtitle' => 'Gérez votre catalogue produit'
        ]);

        return view('seller/products/index', $data);
    }

    // Affiche les détails d'un produit
    public function show($id = null)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Produit non trouvé');
        }

        return view('Seller/Products/show', ['product' => $product]);
    }

    // Affiche le formulaire de création
    public function new()
    {
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        $data = array_merge($this->sellerData, [
            'categories' => $categories,
            'title' => 'Nouveau Produit',
            'subtitle' => 'Ajoutez un produit à votre catalogue'
        ]);

        return view('seller/products/create', $data);
    }

    // Traite le formulaire de création soumis
    public function create()
    {
        $input = $this->request->getPost();
        $input['seller_id'] = $this->getSellerId();
        $input['product_status'] = defined('STATUS_PENDING') ? STATUS_PENDING : 1;

        if ($newId = $this->productModel->insert($input)) {
            return redirect()->to('seller/products/' . $newId . '/edit')->with('message', 'Produit créé ! Vous pouvez maintenant ajouter des photos.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', implode('<br>', $this->productModel->errors()));
    }

    // Affiche le formulaire de modification
    public function edit($productId)
    {
        $product = $this->verifyProductOwnership((int) $productId);
        if (!$product) {
            return redirect()->to('seller/products')->with('error', 'Produit introuvable ou accès refusé.');
        }

        $photos = $this->photoModel->where('product_id', $productId)->orderBy('display_order', 'ASC')->findAll();
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        $data = array_merge($this->sellerData, [
            'product' => $product,
            'photos' => $photos,
            'categories' => $categories,
            'title' => 'Modifier le produit',
            'subtitle' => 'Mettez à jour les informations et les photos'
        ]);

        return view('seller/products/edit', $data);
    }


    // Traite la mise à jour des infos texte du produit
    public function update(int $productId)
    {
        $product = $this->verifyProductOwnership($productId);
        if (!$product) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }

        $postData = $this->request->getPost();
        unset($postData[csrf_token()]);
        unset($postData['_method']);

        // Filtrage intelligent : on compare manuellement avec les données actuelles
        foreach ($postData as $key => $value) {
            $currentValue = $product->$key ?? null;
            $valNormalized = is_string($value) ? str_replace("\r\n", "\n", $value) : $value;
            $curNormalized = is_string($currentValue) ? str_replace("\r\n", "\n", $currentValue) : $currentValue;

            if ($valNormalized == $curNormalized) {
                unset($postData[$key]);
                continue;
            }

            if ($valNormalized === '' && is_null($currentValue)) {
                unset($postData[$key]);
            }
        }

        $product->fill($postData);

        if (!$product->hasChanged()) {
            return redirect()->to("seller/products/$productId/edit")->with('message', 'Aucune modification nécessaire.');
        }

        try {
            if ($this->productModel->save($product)) {
                return redirect()->to("seller/products/$productId/edit")->with('message', 'Informations mises à jour.');
            }
        } catch (DataException) {
            return redirect()->to("seller/products/$productId/edit")->with('message', 'Aucune modification nécessaire.');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', implode('<br>', $this->productModel->errors()));
    }

    // Supprime définitivement un produit
    public function delete(?int $productId = null)
    {
        if (!$productId) {
            return $this->responseOrFail('ID manquant', 404);
        }

        $product = $this->verifyProductOwnership((int) $productId);
        if (!$product) {
            return redirect()->to('seller/products')->with('error', "Action non autorisée.");
        }

        $forceDelete = true;
        if ($this->productModel->delete($productId, $forceDelete)) {
            return redirect()->to('seller/products')->with('message', 'Produit supprimé définitivement.');
        }

        $errors = $this->productModel->errors();
        $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Erreur inconnue.';

        return redirect()->to('seller/products')->with('error', 'Erreur : ' . $errorMsg);
    }
}
