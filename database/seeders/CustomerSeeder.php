<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone' => '+1234567890', 'address' => '123 Main St, City, Country'],
            ['name' => 'Bob Smith', 'email' => 'bob@example.com', 'phone' => '+1234567891', 'address' => '456 Oak Ave, City, Country'],
            ['name' => 'Carol Williams', 'email' => 'carol@example.com', 'phone' => '+1234567892', 'address' => '789 Pine Rd, City, Country'],
            ['name' => 'David Brown', 'email' => 'david@example.com', 'phone' => '+1234567893', 'address' => '321 Elm St, City, Country'],
            ['name' => 'Emma Davis', 'email' => 'emma@example.com', 'phone' => '+1234567894', 'address' => '654 Maple Dr, City, Country'],
            ['name' => 'Frank Miller', 'email' => null, 'phone' => '+1234567895', 'address' => '987 Cedar Ln, City, Country'],
            ['name' => 'Grace Wilson', 'email' => 'grace@example.com', 'phone' => null, 'address' => '147 Birch Ct, City, Country'],
            ['name' => 'Henry Moore', 'email' => 'henry@example.com', 'phone' => '+1234567897', 'address' => '258 Spruce Way, City, Country'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
