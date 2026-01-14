<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockLog;
use App\Repositories\Contracts\PurchaseRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * PurchaseService handles business logic for purchase operations.
 * This includes creating purchases and automatically updating product stock.
 */
class PurchaseService
{
    public function __construct(
        protected PurchaseRepositoryInterface $purchaseRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Create a new purchase with items and update product stock.
     * 
     * @param array $purchaseData Purchase header data (supplier_id, date)
     * @param array $items Array of items with product_id, quantity, unit_price
     * @return Purchase
     * @throws \Exception
     */
    public function createPurchase(array $purchaseData, array $items): Purchase
    {
        return DB::transaction(function () use ($purchaseData, $items) {
            // Calculate total
            $totalAmount = 0;
            foreach ($items as $item) {
                $totalAmount += $item['quantity'] * $item['unit_price'];
            }

            // Create purchase
            $purchase = $this->purchaseRepository->create([
                'supplier_id' => $purchaseData['supplier_id'],
                'date' => $purchaseData['date'],
                'total_amount' => $totalAmount,
            ]);

            // Create purchase items and update stock
            foreach ($items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);

                // Add to stock
                $this->productRepository->updateStock($item['product_id'], $item['quantity']);

                // Log stock change
                StockLog::create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'type' => 'purchase',
                    'reference_id' => $purchase->id,
                ]);
            }

            return $purchase->load(['items.product', 'supplier']);
        });
    }
}
