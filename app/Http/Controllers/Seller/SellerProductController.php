<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Transformers\ProductTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'.ProductTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-products')->except('index');
        $this->middleware('can:view,seller')->only('index');
        $this->middleware('can:sale,seller')->only('store');
        $this->middleware('can:edit-product,seller')->only('update');
        $this->middleware('can:delete-product,seller')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(Request $request, Seller $seller): JsonResponse
    {
        $user = $request->user();
        if ($user->tokenCan('read-general') || $user->tokenCan('manage-products')) {
            $products = $seller->products;

            return $this->showAll($products);
        }
        throw new AuthorizationException('Invalid scope(s)');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $seller): JsonResponse
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seller $seller, Product $product): JsonResponse
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status'   => 'in:'.Product::AVAILABLE_PRODUCT.','.Product::UNAVAILABLE_PRODUCT,
            'image'    => 'image',
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);
        $product->fill($request->only(['name', 'description', 'quantity']));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->categories()->count() == 0) {
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if ($product->isClean()) {
            return $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seller $seller, Product $product): JsonResponse
    {
        $this->checkSeller($seller, $product);

        Storage::delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    /**
     * @throws HttpException
     */
    protected function checkSeller(Seller $seller, Product $product): void
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'The specified user is not the actual seller of the product.');
        }
    }
}
