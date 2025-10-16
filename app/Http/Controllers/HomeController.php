<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class HomeController extends Controller
{
    public function index()
    {
        // Categories - Danh mục nổi bật
        $categories = Category::query()
            ->where('category_is_display', 1)
            ->orderBy('category_id', 'asc')
            ->limit(10)
            ->get();

        // Featured Products - Flash Sale (có discount)
        $featuredProducts = Product::query()
            ->with(['category', 'images' => function($query) {
                $query->where('image_is_display', 1)->orderBy('image_id', 'asc');
            }])
            ->withMin('variants as min_price', 'product_variant_price')
            ->whereHas('variants', function($query) {
                $query->whereNotNull('discount_id')
                      ->where('product_variant_is_stock', 1)
                      ->where('product_variant_is_display', 1);
            })
            ->where('product_is_display', 1)
            ->orderByDesc('product_view_count')
            ->limit(10)
            ->get();

        // Bestseller Products
        $bestsellerProducts = Product::query()
            ->with(['category', 'images' => function($query) {
                $query->where('image_is_display', 1)->orderBy('image_id', 'asc');
            }])
            ->withMin('variants as min_price', 'product_variant_price')
            ->whereHas('variants', function($query) {
                $query->where('product_variant_is_bestseller', 1)
                      ->where('product_variant_is_stock', 1)
                      ->where('product_variant_is_display', 1);
            })
            ->where('product_is_display', 1)
            ->orderByDesc('product_view_count')
            ->limit(10)
            ->get();

        // New Products
        $newProducts = Product::query()
            ->with(['category', 'images' => function($query) {
                $query->where('image_is_display', 1)->orderBy('image_id', 'asc');
            }])
            ->withMin('variants as min_price', 'product_variant_price')
            ->whereHas('variants', function($query) {
                $query->where('product_variant_is_stock', 1)
                      ->where('product_variant_is_display', 1);
            })
            ->where('product_is_display', 1)
            ->orderByDesc('product_id')
            ->limit(10)
            ->get();

        // Suppliers
        $suppliers = Supplier::query()->limit(12)->get();

        return view('home', compact(
            'categories',
            'featuredProducts', 
            'bestsellerProducts',
            'newProducts',
            'suppliers'
        ));
    }
}