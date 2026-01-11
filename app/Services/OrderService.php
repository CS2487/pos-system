<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
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
        return DB::transaction(function () use ($orderData, $items) {
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
            }

            return $order->load(['items.product', 'customer', 'user']);
        });
    }

    /**
     * Get sales statistics for dashboard.
     */
    public function getSalesStats(?int $userId = null): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        $todayQuery = Order::where('created_at', '>=', $today);
        $monthQuery = Order::where('created_at', '>=', $thisMonth);

        if ($userId) {
            $todayQuery->where('user_id', $userId);
            $monthQuery->where('user_id', $userId);
        }

        // Clone queries for count to avoid modifying the original query object if it were reused (though here we build fresh or use cloning implicitly by method calls, explicit variable separation is safer or just re-apply)
        // Actually, in Laravel query builder, calls like sum() and count() execute the query.
        // But we need to be careful not to reuse the same builder instance for multiple aggregates if they modify state.
        // Better approach: create base query logic or apply scopes.
        
        // Simpler implementation:
        return [
            'today_sales' => (clone $todayQuery)->sum('total'),
            'today_orders' => (clone $todayQuery)->count(),
            'month_sales' => (clone $monthQuery)->sum('total'),
            'month_orders' => (clone $monthQuery)->count(),
        ];
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
            }

            return $order;
        });
    }
}
