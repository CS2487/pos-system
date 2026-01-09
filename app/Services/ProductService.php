<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;

/**
 * ProductService handles business logic for product operations.
 */
class ProductService
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get products with low stock alert.
     */
    public function getLowStockProducts(int $threshold = 10)
    {
        return $this->productRepository->lowStock($threshold);
    }

    /**
     * Get top selling products based on order items.
     */
    public function getTopProducts(int $limit = 5)
    {
        return \App\Models\Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
