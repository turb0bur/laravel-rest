<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view,transaction')->only('show');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction): JsonResponse
    {
        $seller = $transaction->product->seller;

        return $this->showOne($seller);
    }
}
