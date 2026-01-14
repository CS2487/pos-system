<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Category;
use App\Models\StockLog;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * OrderService handles business logic for order/sales operations.
 * This includes creating orders, calculating totals, and updating stock.
 */
class OrderService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}




//عدلت هنا
    /*
     * @param array $orderData
     * @param array $items
     * @return void
     */
    public function updateOrder(    
        array $orderData
    ): int  {
        $order = $this->orderRepository->find($orderData["id"]);
        $this->orderRepository->update($order->id, $orderData);
        return $order->id;
    }
    /**
     * Create a new order with items and update product stock.
     * 
     * @param array $orderData Order header data (customer_id, user_id, tax, discount, etc.)
     * @param array $items Array of items with product_id, quantity, unit_price
     * @return Order
     * @throws \Exception
     */
    public function createOrder(array $orderData, array $items): Order
    {
        $result = DB::transaction(function () use ($orderData, $items) {
            // Calculate totals
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $orderData['tax'] ?? 0;
            $discount = $orderData['discount'] ?? 0;
            $total = $subtotal + $tax - $discount;

            // Create order
            $order = $this->orderRepository->create([
                'customer_id' => $orderData['customer_id'] ?? null,
                'user_id' => $orderData['user_id'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => $orderData['status'] ?? 'completed',
                'payment_method' => $orderData['payment_method'] ?? 'cash',
                'received_amount' => $orderData['received_amount'] ?? $total,
                'change_amount' => $orderData['change_amount'] ?? 0,
            ]);

            // Create order items and update stock
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);

                // Deduct stock
                $this->productRepository->updateStock($item['product_id'], -$item['quantity']);

                // Log stock change
                StockLog::create([
                    'product_id' => $item['product_id'],
                    'quantity' => -$item['quantity'],
                    'type' => 'sale',
                    'reference_id' => $order->id,
                ]);
            }

            return $order->load(['items.product', 'customer', 'user']);
        });

        // Clear dashboard cache (after transaction commits successfully - or keeping it simple inside if transaction works)
        // Ideally should be outside, but inside closure is fine as it throws on error
        \Illuminate\Support\Facades\Cache::forget('sales_stats_all');
        \Illuminate\Support\Facades\Cache::forget('sales_stats_' . $orderData['user_id']);
        
        return $result;
    }

    /**
     * Get sales statistics for dashboard.
     */
    /**
     * Get sales statistics for dashboard.
     */
    public function getSalesStats(?int $userId = null): array
    {
        $cacheKey = 'sales_stats_' . ($userId ?? 'all');
        
        return \Illuminate\Support\Facades\Cache::remember($cacheKey, 600, function () use ($userId) {
            $today = now()->startOfDay();
            $thisMonth = now()->startOfMonth();

            $todayQuery = Order::where('created_at', '>=', $today);
            $monthQuery = Order::where('created_at', '>=', $thisMonth);

            if ($userId) {
                $todayQuery->where('user_id', $userId);
                $monthQuery->where('user_id', $userId);
            }

            return [
                'today_sales' => (clone $todayQuery)->sum('total'),
                'today_orders' => (clone $todayQuery)->count(),
                'month_sales' => (clone $monthQuery)->sum('total'),
                'month_orders' => (clone $monthQuery)->count(),
            ];
        });
    }
    
    /**
     * Mark an order as returned and restore stock.
     */
    public function returnOrder(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            if ($order->is_return) {
                throw new \Exception("Order is already returned.");
            }

            $order->update([
                'is_return' => true,
                'status' => 'canceled'
            ]);

            foreach ($order->items as $item) {
                $this->productRepository->updateStock($item->product_id, $item->quantity);

                // Log stock change
                StockLog::create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'type' => 'return',
                    'reference_id' => $order->id,
                ]);
            }
            
            // Clear dashboard cache
            \Illuminate\Support\Facades\Cache::forget('sales_stats_all');
            \Illuminate\Support\Facades\Cache::forget('sales_stats_' . $order->user_id);

            return $order;
        });
    }

    /**
     * Get purchase statistics for dashboard.
     */
    public function getPurchaseStats(): array
    {
        return \Illuminate\Support\Facades\Cache::remember('purchase_stats', 600, function () {
            $today = now()->startOfDay();
            $thisMonth = now()->startOfMonth();

            return [
                'today_purchases' => Purchase::where('created_at', '>=', $today)->sum('total_amount'),
                'today_purchases_count' => Purchase::where('created_at', '>=', $today)->count(),
                'month_purchases' => Purchase::where('created_at', '>=', $thisMonth)->sum('total_amount'),
                'month_purchases_count' => Purchase::where('created_at', '>=', $thisMonth)->count(),
                'total_suppliers' => Supplier::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
            ];
        });
    }
}
