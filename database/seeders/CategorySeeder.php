<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic devices and gadgets',
                'sort_order' => 1,
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel for all ages',
                'sort_order' => 2,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Everything for your home and garden',
                'sort_order' => 3,
            ],
            [
                'name' => 'Books',
                'description' => 'Books, magazines, and educational materials',
                'sort_order' => 4,
            ],
            [
                'name' => 'Sports & Outdoors',
                'description' => 'Sports equipment and outdoor gear',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::create([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'sort_order' => $categoryData['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
