<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // اختر فئة موجودة أو ضع 1 كافتراضي
        $category = Category::inRandomOrder()->first();

        return [
            'category_id' => $category?->id ?? 1,
            'name' => $this->faker->words(3, true),              // اسم المنتج 3 كلمات
            'sku' => strtoupper($this->faker->unique()->bothify('???-###')), // SKU فريد مثل ABC-123
            'price' => $this->faker->randomFloat(2, 5, 200),     // سعر بين 5 و200
            'stock' => $this->faker->numberBetween(0, 100),      // كمية المخزون
            'description' => $this->faker->optional()->sentence(), // وصف اختياري
            'image' => $this->faker->optional()->imageUrl(400, 400, 'products'), // صورة اختياري
        ];
    }
}
