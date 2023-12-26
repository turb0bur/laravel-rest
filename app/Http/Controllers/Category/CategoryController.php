<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->allowedAdminAction();

        $rules = [
            'name'        => 'required',
            'description' => 'required',
        ];

        $this->validate($request, $rules);

        $newCategory = Category::create($request->all());

        return $this->showOne($newCategory, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Category $category)
    {
        $this->allowedAdminAction();

        $category->fill($request->only(['name', 'description']));

        if ($category->isClean()) {
            return $this->errorResponse('You need to specify any different value to update', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $category->save();

        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $this->allowedAdminAction();

        $category->delete();

        return $this->showOne($category);
    }
}
