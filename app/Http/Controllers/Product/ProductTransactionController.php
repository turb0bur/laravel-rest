<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product): JsonResponse
    {
        $this->allowedAdminAction();

        $transactions = $product->transactions;

        return $this->showAll($transactions);
    }
}
