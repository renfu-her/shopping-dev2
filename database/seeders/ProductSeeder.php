<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            [
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'short_description' => 'Premium wireless headphones with amazing sound quality',
                'price' => 1500,
                'sale_price' => 1200,
                'sku' => 'WH-001',
                'stock_quantity' => 50,
                'weight' => 0.3,
                'dimensions' => '20x15x8 cm',
                'is_featured' => true,
            ],
            [
                'name' => 'Smartphone Case',
                'description' => 'Durable protective case for smartphones',
                'short_description' => 'Protect your phone with this premium case',
                'price' => 4000,
                'sale_price' => 3000,
                'sku' => 'SC-001',
                'stock_quantity' => 100,
                'weight' => 0.1,
                'dimensions' => '15x8x2 cm',
            ],
            [
                'name' => 'Cotton T-Shirt',
                'description' => 'Comfortable cotton t-shirt in various colors',
                'short_description' => 'Soft and comfortable cotton t-shirt',
                'price' => 1000,
                'sale_price' => 800,
                'sku' => 'TS-001',
                'stock_quantity' => 200,
                'weight' => 0.2,
                'dimensions' => '30x25x2 cm',
            ],
            [
                'name' => 'Garden Tool Set',
                'description' => 'Complete set of essential garden tools',
                'short_description' => 'Everything you need for your garden',
                'price' => 1000,
                'sale_price' => 800,
                'sku' => 'GT-001',
                'stock_quantity' => 30,
                'weight' => 2.5,
                'dimensions' => '40x30x10 cm',
                'is_featured' => true,
            ],
            [
                'name' => 'Programming Book',
                'description' => 'Comprehensive guide to modern programming',
                'short_description' => 'Learn programming from scratch',
                'price' => 600,
                'sale_price' => 500,
                'sku' => 'BK-001',
                'stock_quantity' => 75,
                'weight' => 0.8,
                'dimensions' => '25x18x3 cm',
            ],
            [
                'name' => 'Yoga Mat',
                'description' => 'Non-slip yoga mat for home workouts',
                'short_description' => 'Perfect for yoga and fitness',
                'price' => 1000,
                'sale_price' => 800,
                'sku' => 'YM-001',
                'stock_quantity' => 60,
                'weight' => 1.2,
                'dimensions' => '180x60x0.5 cm',
            ],
            [
                'name' => 'Coffee Maker',
                'description' => 'Automatic coffee maker with timer',
                'short_description' => 'Start your day with perfect coffee',
                'price' => 1000,
                'sale_price' => 800,
                'sku' => 'CM-001',
                'stock_quantity' => 25,
                'weight' => 3.0,
                'dimensions' => '35x25x40 cm',
                'is_featured' => true,
            ],
            [
                'name' => 'Running Shoes',
                'description' => 'Comfortable running shoes for all terrains',
                'short_description' => 'Perfect for running and jogging',
                'price' => 1000,
                'sale_price' => 800,
                'sku' => 'RS-001',
                'stock_quantity' => 40,
                'weight' => 0.8,
                'dimensions' => '30x15x12 cm',
            ],
        ];

        foreach ($products as $index => $productData) {
            $category = $categories->get($index % $categories->count());
            
            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'description' => $productData['description'],
                'short_description' => $productData['short_description'],
                'price' => $productData['price'],
                'sale_price' => $productData['sale_price'] ?? null,
                'sku' => $productData['sku'],
                'stock_quantity' => $productData['stock_quantity'],
                'weight' => $productData['weight'],
                'dimensions' => $productData['dimensions'],
                'category_id' => $category->id,
                'is_active' => true,
                'is_featured' => $productData['is_featured'] ?? false,
            ]);
        }
    }
}
