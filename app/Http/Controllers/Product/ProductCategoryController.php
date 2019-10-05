<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class productcategorycontroller extends apicontroller
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
     *
     * @param \app\product $product
     * @return \illuminate\http\response
     */
    public function index(product $product)
    {
        $categories = $product->categories;

        return $this->showall($categories);
    }

    /**
     * update the specified resource in storage.
     *
     * @param \illuminate\http\request $request
     * @param \app\product             $product
     * @param \app\category            $category
     * @return \illuminate\http\response
     */
    public function update(request $request, product $product, category $category)
    {
        $product->categories()->syncwithoutdetaching([$category->id]);

        return $this->showall($product->categories);
    }

    /**
     * remove the specified resource from storage.
     *
     * @param \app\product  $product
     * @param \app\category $category
     * @return \illuminate\http\response
     */
    public function destroy(product $product, category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorresponse('the specified category is not a category of this product', 404);
        }

        $product->categories()->detach($category->id);

        return $this->showall($product->categories);
    }
}
