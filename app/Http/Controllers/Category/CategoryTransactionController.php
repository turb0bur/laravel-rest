<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class CategoryTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Category $category): JsonResponse
    {
        $this->allowedAdminAction();

        $transactions = $category->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();

        return $this->showAll($transactions);
    }
}
