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
    public function getSalesStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        return [
            'today_sales' => Order::where('created_at', '>=', $today)->sum('total'),
            'today_orders' => Order::where('created_at', '>=', $today)->count(),
            'month_sales' => Order::where('created_at', '>=', $thisMonth)->sum('total'),
            'month_orders' => Order::where('created_at', '>=', $thisMonth)->count(),
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
