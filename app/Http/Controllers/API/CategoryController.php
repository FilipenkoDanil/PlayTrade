<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        return new CategoryResource($this->categoryService->create($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category->load(['game.categories' => function ($query) {
            $query->withCount('offers');
        }, 'offers.seller', 'servers', 'attributes', 'unit', 'offers.attributes', 'offers.server']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return new CategoryResource($this->categoryService->update($category, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::transaction(function () use ($category) {
            $category->delete();
        });

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
