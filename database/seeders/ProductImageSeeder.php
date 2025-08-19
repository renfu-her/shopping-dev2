<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the products directory if it doesn't exist
        if (!Storage::disk('public')->exists('products')) {
            Storage::disk('public')->makeDirectory('products');
        }

        $products = Product::all();

        foreach ($products as $index => $product) {
            // Create a simple placeholder image for each product
            $imagePath = $this->createPlaceholderImage($product->name, $index + 1);
            
            // Create the product image record
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $imagePath,
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_primary' => true, // Make the first image primary
            ]);
        }
    }

    /**
     * Create a simple placeholder image using GD
     */
    private function createPlaceholderImage(string $productName, int $index): string
    {
        $width = 400;
        $height = 400;
        
        // Create image
        $image = imagecreate($width, $height);
        
        // Define colors
        $bgColor = imagecolorallocate($image, 240, 240, 240); // Light gray background
        $textColor = imagecolorallocate($image, 100, 100, 100); // Dark gray text
        $borderColor = imagecolorallocate($image, 200, 200, 200); // Border color
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Draw border
        imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);
        
        // Add text
        $text = "Product #{$index}";
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = ($width - $textWidth) / 2;
        $y = ($height - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        // Save image
        $filename = "products/product-{$index}.jpg";
        $fullPath = storage_path("app/public/{$filename}");
        
        // Ensure directory exists
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        imagejpeg($image, $fullPath, 85);
        imagedestroy($image);
        
        return $filename;
    }
}
