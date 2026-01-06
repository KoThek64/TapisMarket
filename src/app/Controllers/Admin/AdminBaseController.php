<?php

namespace App\Controllers\Admin;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SellerModel;
use App\Models\UserModel;
use App\Models\OrderModel;
use App\Models\ReviewModel;
use App\Models\CategoryModel;

class AdminBaseController extends BaseController
{
    protected $productModel;
    protected $sellerModel;
    protected $userModel;
    protected $orderModel;
    protected $reviewModel;
    protected $categoryModel;

    protected $adminData = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->productModel   = new ProductModel();
        $this->sellerModel    = new SellerModel();
        $this->userModel      = new UserModel();
        $this->orderModel     = new OrderModel();
        $this->reviewModel    = new ReviewModel();
        $this->categoryModel  = new CategoryModel();

        $this->adminData = [
            'pendingProductsCount' => $this->productModel->countPendingProducts(),
            'pendingSellersCount'  => $this->sellerModel->countSellersPendingValidation(),
        ];
    }
}