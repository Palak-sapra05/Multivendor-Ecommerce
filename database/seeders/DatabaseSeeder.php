<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // 2. Run Category Seeder
        $this->call(CategorySeeder::class);

        // 3. Create Sellers and Stores
        $seller1 = User::create([
            'name' => 'Alice Seller',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
            'role' => 'seller'
        ]);
        $store1 = Store::create([
            'user_id' => $seller1->id,
            'name' => 'Alice Electronics',
            'slug' => 'alice-electronics',
            'description' => 'Best gadgets.',
            'is_approved' => true,
            'status' => 'active'
        ]);

        $seller2 = User::create([
            'name' => 'Bob Seller',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
            'role' => 'seller'
        ]);
        $store2 = Store::create([
            'user_id' => $seller2->id,
            'name' => 'Bob Clothing',
            'slug' => 'bob-clothing',
            'description' => 'Cool clothes.',
            'is_approved' => true,
            'status' => 'active'
        ]);

        // 4. Create User as Seller (for testing)
        $user = User::updateOrCreate(
            ['email' => 'saprapalak69@gmail.com'],
            [
                'name' => 'Palak Sapra',
                'role' => 'seller',
                'password' => bcrypt('password'), // or nullable since it's google login
            ]
        );
        Store::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Palak Store',
                'slug' => 'palak-store',
                'description' => 'My awesome store.',
                'is_approved' => true,
                'status' => 'active'
            ]
        );

        $electronicsId = \App\Models\Category::where('slug', 'electronics')->first()->id;
        $fashionId = \App\Models\Category::where('slug', 'fashion')->first()->id;

        // 3. Create Products
        Product::create([
            'store_id' => $store1->id,
            'category_id' => $electronicsId,
            'name' => 'Smartphone',
            'slug' => 'smartphone',
            'price' => 500.00,
            'stock' => 10,
        ]);
        Product::create([
            'store_id' => $store1->id,
            'category_id' => $electronicsId,
            'name' => 'Headphones',
            'slug' => 'headphones',
            'price' => 100.00,
            'stock' => 20,
        ]);

        Product::create([
            'store_id' => $store2->id,
            'category_id' => $fashionId,
            'name' => 'T-Shirt',
            'slug' => 't-shirt',
            'price' => 20.00,
            'stock' => 50,
        ]);
    }
}
