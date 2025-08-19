<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'primaryImage']);

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Featured filter
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Sort
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        return view('pages.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'images', 'primaryImage']);
        return view('pages.products.show', compact('product'));
    }

    public function category(Category $category)
    {
        
        $products = $category->products()
            ->active()
            ->with(['primaryImage'])
            ->paginate(12);

        return view('pages.products.index', compact('products', 'category'));
    }
}
