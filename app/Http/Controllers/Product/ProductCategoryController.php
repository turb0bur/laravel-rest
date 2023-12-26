<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only('index');
        $this->middleware('auth:api')->except('index');
        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:add-category,product')->only('update');
        $this->middleware('can:delete-category,product')->only('destroy');
    }

    /**
     * display a listing of the resource.
     */
    public function index(product $product): JsonResponse
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Category $category): JsonResponse
    {
        $product->categories()->syncwithoutdetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category): JsonResponse
    {
        if (! $product->categories()->find($category->id)) {
            return $this->errorResponse('the specified category is not a category of this product', Response::HTTP_NOT_FOUND);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
