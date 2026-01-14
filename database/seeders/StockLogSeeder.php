<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockLog;
use App\Models\Product;

class StockLogSeeder extends Seeder
{
    public function run(): void
    {
        Product::all()->each(function ($product) {
            StockLog::factory()->count(rand(1, 5))->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
