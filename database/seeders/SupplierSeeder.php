<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'Tech Supplies Inc', 'email' => 'contact@techsupplies.com', 'phone' => '+1555123456', 'address' => '100 Tech Park, Silicon Valley, USA'],
            ['name' => 'Fashion Wholesale Co', 'email' => 'sales@fashionwholesale.com', 'phone' => '+1555123457', 'address' => '200 Fashion District, New York, USA'],
            ['name' => 'Global Food Distributors', 'email' => 'info@globalfood.com', 'phone' => '+1555123458', 'address' => '300 Food Plaza, Chicago, USA'],
            ['name' => 'Home & Garden Suppliers', 'email' => 'orders@homegardens.com', 'phone' => '+1555123459', 'address' => '400 Garden Way, Portland, USA'],
            ['name' => 'Sports Equipment Ltd', 'email' => 'support@sportsequip.com', 'phone' => '+1555123460', 'address' => '500 Sports Complex, Denver, USA'],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
