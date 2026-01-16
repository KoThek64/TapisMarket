<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Controllers\BaseController;

// Import des modèles pour le mapping
use App\Models\ProductModel;
use App\Models\SellerModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
use App\Models\CategoryModel;

class AdminBaseController extends BaseController
{
    protected array $adminData = [];
    private array $loadedModels = [];

    // Initialisation des modèles communs et des données admin
    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);

        $this->adminData = [
            'pendingProductsCount' => $this->productModel->countPendingProducts(),
            'pendingSellersCount' => $this->sellerModel->countSellersPendingValidation(),
        ];
    }

    // Chargement dynamique des modèles
    public function __get($name)
    {
        if (isset($this->loadedModels[$name])) {
            return $this->loadedModels[$name];
        }

        $modelMap = [
            'productModel' => ProductModel::class,
            'sellerModel' => SellerModel::class,
            'userModel' => UserModel::class,
            'orderModel' => OrderModel::class,
            'reviewModel' => ReviewModel::class,
            'categoryModel' => CategoryModel::class,
        ];

        if (array_key_exists($name, $modelMap)) {
            $class = $modelMap[$name];
            $this->loadedModels[$name] = new $class();
            return $this->loadedModels[$name];
        }

        return null;
    }
}
