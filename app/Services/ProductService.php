<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function getAllProducts(array $filters)
    {
        $query = Product::query();

        // Apply filters
        if (isset($filters['sku'])) {
            $query->where('sku', 'like', '%' . $filters['sku'] . '%');
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        // Paginate results
        return $query->paginate(request('per_page', 10));  // 10 items per page, can be changed
    }

    public function createProduct(array $data): Product
    {
        return Product::create([
            'name' => $data['name'],
            'sku' => $data['sku'],
            'price' => $data['price'],
            'initial_stock_quantity' => $data['initial_stock_quantity'],
            'current_stock_quantity' => $data['initial_stock_quantity'], // Initial stock same as quantity
            'category_id' => $data['category_id'] ?? null,
        ]);
    }

    public function getProductById(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
