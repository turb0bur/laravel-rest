<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only('index');
        $this->middleware('can:view, buyer')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()
            ->with('product')
            ->get()
            ->pluck('product');

        return $this->showAll($products);
    }
}
