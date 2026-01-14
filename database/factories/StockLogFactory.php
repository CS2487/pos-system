<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\StockLog;
use App\Models\Product;

class StockLogFactory extends Factory
{
    protected $model = StockLog::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'quantity' => $this->faker->numberBetween(-20, 50),
            'type' => $this->faker->randomElement(['sale', 'purchase', 'adjustment', 'return']),
            'reference_id' => null,
        ];
    }
}
