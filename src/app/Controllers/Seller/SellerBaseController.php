<?php

namespace App\Controllers\Seller;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\CategoryModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use App\Models\ProductPhotoModel;
use App\Models\ReviewModel;
use App\Models\SellerModel;
use App\Models\OrderModel; 


class SellerBaseController extends BaseController
{
    protected array $sellerData = [];

    // Modèles communs pour les contrôleurs vendeur
    private array $loadedModels = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
    }

    // Chargement dynamique des modèles
    public function __get($name)
    {
        if (isset($this->loadedModels[$name])) {
            return $this->loadedModels[$name];
        }

        $modelMap = [
            'productModel'   => ProductModel::class,
            'orderItemModel' => OrderItemModel::class,
            'reviewModel'    => ReviewModel::class,
            'photoModel'     => ProductPhotoModel::class,
            'categoryModel'  => CategoryModel::class,
            'sellerModel'    => SellerModel::class,
            'orderModel'     => OrderModel::class, 
        ];

        if (array_key_exists($name, $modelMap)) {
            $class = $modelMap[$name];
            $this->loadedModels[$name] = new $class();
            return $this->loadedModels[$name];
        }

        return null;
    }

    // Récupère l'ID du vendeur actuellement connecté
    protected function getSellerId(): int
    {
        return (int)session()->get('user_id');
    }
 
    // Réponse d'erreur adaptée au contexte (AJAX ou redirection)
    protected function responseOrFail($message, $code = 400, $isValidation = false)
    {
         if ($this->request->isAJAX()) {
             $data = $isValidation ? ['errors' => $message] : ['error' => $message];
             return $this->response->setJSON($data)->setStatusCode($code);
         }
         
         $msgString = is_array($message) ? implode(', ', $message) : $message;
         return redirect()->back()->with('error', $msgString);
    }
}