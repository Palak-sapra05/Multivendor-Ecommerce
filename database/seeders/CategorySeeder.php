<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Electronics', 'slug' => 'electronics'],
            ['name' => 'Home & Living', 'slug' => 'home-living'],
            ['name' => 'Beauty', 'slug' => 'beauty'],
            ['name' => 'Gadgets', 'slug' => 'gadgets'],
            ['name' => 'Kids', 'slug' => 'kids'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }
    }
}
