<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        Order::factory()->count(10)->create()->each(function ($order) {
            $items = OrderItem::factory()->count(rand(1, 10))->make([
                'order_id' => $order->id,
            ]);

            foreach ($items as $item) {
                $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
                $item->product_id = $product->id;
                $item->unit_price = $product->price;
                $item->subtotal = $item->quantity * $item->unit_price;
                $item->save();
            }

            $subtotal = $order->items()->sum('subtotal');
            $tax = $subtotal * 0.15; // 15% tax
            $discount = rand(0, 10);
            $total = $subtotal + $tax - $discount;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'received_amount' => $total,
                'change_amount' => 0,
            ]);
        });
    }
}
