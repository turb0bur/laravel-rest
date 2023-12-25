<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('client.credentials')->only('index');
        $this->middleware('auth:api')->except('index');
        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:add-category,product')->only('update');
        $this->middleware('can:delete-category,product')->only('destroy');
    }

    /**
     * display a listing of the resource.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * update the specified resource in storage.
     *
     * @param Request $request
     * @param Product             $product
     * @param Category            $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(request $request, product $product, category $category)
    {
        $product->categories()->syncwithoutdetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * remove the specified resource from storage.
     *
     * @param Product  $product
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(product $product, category $category)
    {
        if (! $product->categories()->find($category->id)) {
            return $this->errorResponse('the specified category is not a category of this product', 404);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
