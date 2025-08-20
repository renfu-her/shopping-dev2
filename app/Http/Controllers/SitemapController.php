<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate and return sitemap.xml
     */
    public function index()
    {
        $baseUrl = config('app.url');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticPages = [
            '' => ['priority' => '1.0', 'changefreq' => 'daily'],
            '/products' => ['priority' => '0.9', 'changefreq' => 'daily'],
            '/login' => ['priority' => '0.5', 'changefreq' => 'daily'],
            '/register' => ['priority' => '0.5', 'changefreq' => 'daily'],
        ];

        foreach ($staticPages as $path => $settings) {
            $xml .= $this->generateUrl($baseUrl . $path, $settings['priority'], $settings['changefreq']);
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

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600'
        ]);
    }

    /**
     * Generate a URL entry for the sitemap
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
