<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Electronics
            ['category_id' => 1, 'name' => 'Wireless Mouse', 'sku' => 'ELEC-001', 'price' => 25.99, 'stock' => 50, 'description' => 'Ergonomic wireless mouse'],
            ['category_id' => 1, 'name' => 'USB-C Cable', 'sku' => 'ELEC-002', 'price' => 12.99, 'stock' => 100, 'description' => '2m USB-C charging cable'],
            ['category_id' => 1, 'name' => 'Bluetooth Speaker', 'sku' => 'ELEC-003', 'price' => 49.99, 'stock' => 30, 'description' => 'Portable bluetooth speaker'],
            
            // Clothing
            ['category_id' => 2, 'name' => 'Cotton T-Shirt', 'sku' => 'CLO-001', 'price' => 19.99, 'stock' => 75, 'description' => '100% cotton t-shirt'],
            ['category_id' => 2, 'name' => 'Denim Jeans', 'sku' => 'CLO-002', 'price' => 59.99, 'stock' => 40, 'description' => 'Classic fit denim jeans'],
            ['category_id' => 2, 'name' => 'Running Shoes', 'sku' => 'CLO-003', 'price' => 89.99, 'stock' => 25, 'description' => 'Lightweight running shoes'],
            
            // Food & Beverages
            ['category_id' => 3, 'name' => 'Organic Coffee Beans', 'sku' => 'FOOD-001', 'price' => 15.99, 'stock' => 60, 'description' => '500g organic coffee beans'],
            ['category_id' => 3, 'name' => 'Green Tea', 'sku' => 'FOOD-002', 'price' => 8.99, 'stock' => 80, 'description' => 'Premium green tea leaves'],
            ['category_id' => 3, 'name' => 'Dark Chocolate', 'sku' => 'FOOD-003', 'price' => 5.99, 'stock' => 120, 'description' => '70% cocoa dark chocolate bar'],
            
            // Home & Garden
            ['category_id' => 4, 'name' => 'LED Light Bulb', 'sku' => 'HOME-001', 'price' => 7.99, 'stock' => 150, 'description' => 'Energy-efficient LED bulb'],
            ['category_id' => 4, 'name' => 'Garden Hose', 'sku' => 'HOME-002', 'price' => 29.99, 'stock' => 35, 'description' => '15m expandable garden hose'],
            ['category_id' => 4, 'name' => 'Plant Pot', 'sku' => 'HOME-003', 'price' => 12.99, 'stock' => 45, 'description' => 'Ceramic plant pot with drainage'],
            
            // Sports & Outdoors
            ['category_id' => 5, 'name' => 'Yoga Mat', 'sku' => 'SPORT-001', 'price' => 34.99, 'stock' => 40, 'description' => 'Non-slip yoga mat'],
            ['category_id' => 5, 'name' => 'Water Bottle', 'sku' => 'SPORT-002', 'price' => 18.99, 'stock' => 70, 'description' => 'Insulated stainless steel bottle'],
            ['category_id' => 5, 'name' => 'Camping Tent', 'sku' => 'SPORT-003', 'price' => 149.99, 'stock' => 15, 'description' => '4-person camping tent'],
            
            // Books & Stationery
            ['category_id' => 6, 'name' => 'Notebook A5', 'sku' => 'BOOK-001', 'price' => 6.99, 'stock' => 90, 'description' => 'Ruled notebook 200 pages'],
            ['category_id' => 6, 'name' => 'Ballpoint Pen Set', 'sku' => 'BOOK-002', 'price' => 9.99, 'stock' => 110, 'description' => 'Pack of 10 pens'],
            ['category_id' => 6, 'name' => 'Desk Organizer', 'sku' => 'BOOK-003', 'price' => 24.99, 'stock' => 30, 'description' => 'Wooden desk organizer'],
            
            // Low stock items for testing
            ['category_id' => 1, 'name' => 'Laptop Stand', 'sku' => 'ELEC-004', 'price' => 39.99, 'stock' => 5, 'description' => 'Adjustable laptop stand'],
            ['category_id' => 2, 'name' => 'Winter Jacket', 'sku' => 'CLO-004', 'price' => 129.99, 'stock' => 3, 'description' => 'Insulated winter jacket'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
