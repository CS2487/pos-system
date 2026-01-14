<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        Purchase::factory()->count(10)->create()->each(function ($purchase) {
            $items = PurchaseItem::factory()->count(rand(1, 5))->make([
                'purchase_id' => $purchase->id,
            ]);

            foreach ($items as $item) {
                // Use existing product or create one
                $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
                $item->product_id = $product->id;
                $item->save();
            }

            $purchase->update([
                'total_amount' => $purchase->items()->sum(\DB::raw('quantity * unit_price')),
            ]);
        });
    }
}
