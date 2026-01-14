<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'user_id' => User::where('role', 'cashier')->inRandomOrder()->first()?->id ?? User::factory()->state(['role' => 'cashier']),
            'subtotal' => 0,
            'tax' => 0,
            'discount' => 0,
            'total' => 0,
            'status' => 'completed',
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'received_amount' => 0,
            'change_amount' => 0,
            'is_return' => false,
        ];
    }
}
