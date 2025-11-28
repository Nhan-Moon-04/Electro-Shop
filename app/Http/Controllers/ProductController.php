<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Lấy categories cho sidebar filter
        $categories = Category::query()
            ->where('category_is_display', 1)
            ->withCount([
                'products' => function ($query) {
                    $query->where('product_is_display', 1);
                }
            ])
            ->orderBy('category_name', 'asc')
            ->get();

        // Lấy suppliers cho sidebar filter  
        $suppliers = Supplier::query()
            ->withCount([
                'products' => function ($query) {
                    $query->where('product_is_display', 1);
                }
            ])
            ->having('products_count', '>', 0)
            ->orderBy('supplier_name', 'asc')
            ->get();

        // Query sản phẩm với filter
        $query = Product::query()
            ->with([
                'category:category_id,category_name',
                'supplier:supplier_id,supplier_name',
                'images' => function ($q) {
                    $q->where('image_is_display', 1)->orderBy('image_id', 'asc');
                },
                'variants' => function ($q) {
                    $q->where('product_variant_is_stock', 1)
                        ->where('product_variant_is_display', 1)
                        ->orderBy('product_variant_price', 'asc');
                }
            ])
            ->withMin('variants as min_price', 'product_variant_price')
            ->where('product_is_display', 1);

        // Filter theo category
        if ($request->filled('category')) {
            $query->whereIn('category_id', $request->input('category'));
        }

        // Filter theo supplier
        if ($request->filled('supplier')) {
            $query->whereIn('supplier_id', $request->input('supplier'));
        }

        // Filter theo price range
        if ($priceRange = $request->input('price_range')) {
            switch ($priceRange) {
                case 'under_2m':
                    $query->whereHas('variants', fn($q) => $q->where('product_variant_price', '<', 2000000));
                    break;
                case '2m_5m':
                    $query->whereHas('variants', fn($q) => $q->whereBetween('product_variant_price', [2000000, 5000000]));
                    break;
                case '5m_10m':
                    $query->whereHas('variants', fn($q) => $q->whereBetween('product_variant_price', [5000000, 10000000]));
                    break;
                case '10m_20m':
                    $query->whereHas('variants', fn($q) => $q->whereBetween('product_variant_price', [10000000, 20000000]));
                    break;
                case 'over_20m':
                    $query->whereHas('variants', fn($q) => $q->where('product_variant_price', '>', 20000000));
                    break;
            }
        }

        // Filter theo rating
        if ($rating = $request->input('rating')) {
            $query->where('product_rate', '>=', (int) $rating);
        }

        // Search theo keyword
        if ($search = $request->input('q')) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_description', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('category_name', 'like', "%{$search}%");
                  });
            });
        }

        // Sort
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('min_price', 'asc');
                break;
            case 'price_desc':
                $query->orderByDesc('min_price');
                break;
            case 'newest':
                $query->orderByDesc('product_id');
                break;
            case 'bestseller':
                $query->whereHas('variants', fn($q) => $q->where('product_variant_is_bestseller', 1))
                    ->orderByDesc('product_view_count');
                break;
            case 'rating':
                $query->orderByDesc('product_rate');
                break;
            default:
                $query->orderByDesc('product_id');
        }

        $products = $query->paginate(12)->appends($request->query());

        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function show($id)
    {
        $product = Product::with([
            'images' => function($query) {
                $query->where('image_is_display', 1);
            },
            'variants' => function($query) {
                $query->with('discount') // Load discount through variants
                      ->orderBy('product_variant_is_display', 'desc');
            },
            'supplier',
            'details',
            'category'
        ])->findOrFail($id);
        
        // Get related products from same category
        $relatedProducts = Product::with(['images', 'variants.discount'])
            ->where('category_id', $product->category_id)
            ->where('product_id', '!=', $id)
            ->where('product_is_display', 1)
            ->limit(5)
            ->get();
        
        $reviewCount = 0;
        $rating = $product->product_rate ?? 0;
        
        return view('products.show', compact('product', 'relatedProducts', 'reviewCount', 'rating'));
    }

    public function category($categoryId)
    {
        $category = Category::where('category_is_display', 1)->findOrFail($categoryId);

        // Lấy categories cho sidebar filter
        $categories = Category::query()
            ->where('category_is_display', 1)
            ->withCount([
                'products' => function ($query) {
                    $query->where('product_is_display', 1);
                }
            ])
            ->orderBy('category_name', 'asc')
            ->get();

        // Lấy suppliers cho sidebar filter  
        $suppliers = Supplier::query()
            ->withCount([
                'products' => function ($query) {
                    $query->where('product_is_display', 1);
                }
            ])
            ->having('products_count', '>', 0)
            ->orderBy('supplier_name', 'asc')
            ->get();

        $products = Product::query()
            ->with([
                'category',
                'supplier',
                'images' => function ($q) {
                    $q->where('image_is_display', 1)->orderBy('image_id', 'asc');
                },
                'variants' => function ($q) {
                    $q->where('product_variant_is_stock', 1)
                        ->where('product_variant_is_display', 1)
                        ->orderBy('product_variant_price', 'asc');
                }
            ])
            ->withMin('variants as min_price', 'product_variant_price')
            ->where('category_id', $categoryId)
            ->where('product_is_display', 1)
            ->orderByDesc('product_id')
            ->paginate(12);

        return view('products.index', compact('products', 'categories', 'suppliers', 'category'));
    }
}