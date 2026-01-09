<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::with('category')->get();
    }

    public function find(int $id): ?Model
    {
        return Product::with('category')->find($id);
    }

    public function create(array $data): Model
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = Product::find($id);
        return $product ? $product->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $product = Product::find($id);
        return $product ? $product->delete() : false;
    }

    public function search(string $query): Collection
    {
        return Product::where('name', 'like', "%{$query}%")
            ->orWhere('sku', 'like', "%{$query}%")
            ->with('category')
            ->get();
    }

    public function lowStock(int $threshold = 10): Collection
    {
        return Product::where('stock', '<=', $threshold)
            ->with('category')
            ->get();
    }

    public function updateStock(int $productId, int $quantity): bool
    {
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }
        
        $product->stock += $quantity;
        return $product->save();
    }
}
