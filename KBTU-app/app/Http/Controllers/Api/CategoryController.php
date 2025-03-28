<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return response()->json($categories);
    }

    /**
     * POST /api/categories
     * Create a new category.
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            // 'slug' => 'required|string|max:255|unique:categories,slug' // Maybe need later
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($validator->validated());

        return response()->json($category, 201);
    }

    /**
     * GET /api/categories/{category}
     * Retrieve details of a single category.
     */
    public function show(string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();

        return response()->json($category);
    }

    /**
     * PUT/PATCH /api/categories/{category}
     * Update an existing category.
     */
    public function update(Request $request, string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            // 'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id // Maybe need later
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($validator->validated());

        return response()->json($category);
    }

    /**
     * DELETE /api/categories/{category}
     * Delete a category.
     */
    public function destroy(string $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        if ($category->products()->exists()) {
            return response()->json(['message' => 'Cannot delete category with associated products.'], 409);
        }

        $category->delete();

        return response()->json(null, 204);
    }

    /**
     * GET /api/products/{product}/categories
     * Get all categories associated with a product.
     */
    public function getCategoriesForProduct(string $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();
        $categories = $product->categories()->orderBy('name')->get();

        return response()->json($categories);
    }
}
