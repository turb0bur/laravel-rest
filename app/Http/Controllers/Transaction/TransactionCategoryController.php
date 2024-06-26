<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('client.credentials')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Transaction $transaction
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;

        return $this->showAll($categories);
    }
}
