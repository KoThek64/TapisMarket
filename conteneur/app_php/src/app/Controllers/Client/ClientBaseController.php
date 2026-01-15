<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use App\Models\AddressModel;
use App\Models\ReviewModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use RuntimeException;

class ClientBaseController extends BaseController
{
    protected array $clientData = [];

    private array $loadedModels = [];

    // Initialisation des modèles communs et des données client
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->clientData['user'] = $this->getCurrentUser();
    }

    // Chargement dynamique des modèles
    public function __get($name)
    {
        // Si le modèle a déjà été chargé, on le retourne
        if (isset($this->loadedModels[$name])) {
            return $this->loadedModels[$name];
        }

        $modelMap = [
            'orderModel' => OrderModel::class,
            'orderItemModel' => OrderItemModel::class,
            'customerModel' => CustomerModel::class,
            'addressModel' => AddressModel::class,
            'reviewModel' => ReviewModel::class,
            'userModel' => UserModel::class,
            'productModel' => ProductModel::class,
        ];

        // Si le modèle demandé est dans la map, on l'instancie
        if (array_key_exists($name, $modelMap)) {
            $class = $modelMap[$name];
            $this->loadedModels[$name] = new $class();
            return $this->loadedModels[$name];
        }

        return null;
    }

    // Récupération du profil complet de l'utilisateur courant
    protected function getCurrentUser()
    {
        try {
            $userId = user_id();
            // Récupération du profil complet via le modèle customerModel intercepte par le __get
            return $this->customerModel->getFullProfile($userId);
        } catch (RuntimeException $e) {
            return null;
        }
    }
}
