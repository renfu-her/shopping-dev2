<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap.xml for the website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating sitemap.xml...');

        $baseUrl = config('app.url');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticPages = [
            '' => '1.0', // Home page
            '/products' => '0.9', // Products listing
            '/login' => '0.5', // Login page
            '/register' => '0.5', // Register page
        ];

        foreach ($staticPages as $path => $priority) {
            $xml .= $this->generateUrl($baseUrl . $path, $priority, 'daily');
        }

        // Categories
        $categories = Category::active()->get();
        foreach ($categories as $category) {
            $xml .= $this->generateUrl(
                $baseUrl . '/category/' . $category->slug,
                '0.8',
                'weekly',
                $category->updated_at
            );
        }

        // Products
        $products = Product::active()->get();
        foreach ($products as $product) {
            $xml .= $this->generateUrl(
                $baseUrl . '/products/' . $product->slug,
                '0.7',
                'weekly',
                $product->updated_at
            );
        }

        $xml .= '</urlset>';

        // Write to public/sitemap.xml
        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated successfully at: ' . public_path('sitemap.xml'));
        $this->info('Total URLs: ' . (count($staticPages) + $categories->count() + $products->count()));
    }

    /**
     * Generate a URL entry for the sitemap.
     */
    private function generateUrl($url, $priority, $changefreq, $lastmod = null)
    {
        $xml = '  <url>' . "\n";
        $xml .= '    <loc>' . htmlspecialchars($url) . '</loc>' . "\n";
        
        if ($lastmod) {
            $xml .= '    <lastmod>' . $lastmod->toISOString() . '</lastmod>' . "\n";
        } else {
            $xml .= '    <lastmod>' . Carbon::now()->toISOString() . '</lastmod>' . "\n";
        }
        
        $xml .= '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
        $xml .= '    <priority>' . $priority . '</priority>' . "\n";
        $xml .= '  </url>' . "\n";
        
        return $xml;
    }
}
