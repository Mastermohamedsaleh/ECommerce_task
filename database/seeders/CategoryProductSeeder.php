<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Gadgets and Devices']);
        $fashion = Category::create(['name' => 'Fashion', 'slug' => 'fashion', 'description' => 'Clothes and Apparel']);

        Product::create([
            'category_id' => $electronics->id,
            'name'        => 'Laptop Pro 16',
            'slug'        => Str::slug('Laptop Pro 16'),
            'description' => 'High performance laptop for developers',
            'price'       => 1500.00,
            'stock'       => 10,
        ]);

        Product::create([
            'category_id' => $electronics->id,
            'name'        => 'Wireless Headphones',
            'slug'        => Str::slug('Wireless Headphones'),
            'description' => 'Noise-canceling headphones',
            'price'       => 200.00,
            'stock'       => 25,
        ]);

        Product::create([
            'category_id' => $fashion->id,
            'name'        => 'Cotton T-Shirt',
            'slug'        => Str::slug('Cotton T-Shirt'),
            'description' => '100% Organic Cotton',
            'price'       => 35.00,
            'stock'       => 50,
        ]);
    }
}