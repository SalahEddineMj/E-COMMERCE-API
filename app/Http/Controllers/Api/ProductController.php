<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ProductFilter();
        $filterItems = $filter->transform($request); //[['column', 'operator', 'value']]
        $products = Product::where($filterItems)->get();
        return response()->json([
            'products' => ProductResource::collection($products)
    ], 200);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);
        return response()->json(new ProductResource($product), 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json(['product' => new ProductResource($product)], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); // 404 Not Found
        }
        $product->update($data);
        return response()->json(new ProductResource($product), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404); // 404 Not Found
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200); // 200 OK
    }

    public function search(Request $request) {
        $query = $request->query('query');
        $products = Product::where('name', 'like', "%{$query}%")
        ->orWhere('description', 'like', "%{$query}%")->get();
        if($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }
        return response()->json([ProductResource::collection($products)], 200);
    }
}
