<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Retrieve all products with their categories.
     */
    public function index()
    {
        // Используем 'categories' (мн. число) для many-to-many
        $products = Product::with('categories')->orderBy('name')->get();
        return response()->json($products);
    }

    /**
     * POST /api/products
     * Create a new product and attach categories.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $categoryIds = $validatedData['categories'] ?? [];

        $productData = collect($validatedData)->except('categories')->toArray();
        $product = Product::create($productData);

        if (!empty($categoryIds)) {
            $product->categories()->attach($categoryIds);
        }

        return response()->json($product->load('categories'), 201);
    }

    /**
     * GET /api/products/{product}
     * Retrieve a single product with its categories.
     */
    public function show(string $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();
        return response()->json($product);
    }

    /**
     * PUT/PATCH /api/products/{product}
     * Update a product and sync categories.
     * @throws ValidationException
     */
    public function update(Request $request, string $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();
        $categoryIds = $validatedData['categories'] ?? [];

        $productData = collect($validatedData)->except('categories')->toArray();
        $product->update($productData);
        $product->categories()->sync($categoryIds);

        return response()->json($product->load('categories'));
    }

    /**
     * DELETE /api/products/{product}
     * Delete a product.
     */
    public function destroy(string $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();
        $product->delete();

        return response()->json(null, 204);
    }
}
