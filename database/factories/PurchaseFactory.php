<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;
use App\Models\Supplier;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'date' => $this->faker->date(),
            'total_amount' => 0, // Calculated during creation or by items
        ];
    }
}
