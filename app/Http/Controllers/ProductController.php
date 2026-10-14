<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function index(?string $slug = null)
    {
        $categories = Schema::hasTable('categories')
            ? Category::query()->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        $selectedCategory = null;
        $productsQuery = Schema::hasTable('products')
            ? Product::query()->with('category')->where('is_active', true)
            : null;

        if ($slug && Schema::hasTable('categories')) {
            $selectedCategory = Category::query()->where('slug', $slug)->where('is_active', true)->first();
            if ($selectedCategory) {
                $productsQuery?->where('category_id', $selectedCategory->id);
            }
        }

        $products = $productsQuery ? $productsQuery->orderBy('name')->paginate(12) : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);

        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('shop', compact('categories', 'selectedCategory', 'products', 'cartCount', 'siteSettings'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load('category');
        $relatedProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();
        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('products.show', compact('product', 'relatedProducts', 'cartCount', 'siteSettings'));
    }

    public function addToCart(Request $request, Product $product)
    {
        abort_unless($product->is_active, 404);
        $cart = session()->get('cart', []);
        $quantity = (int) ($cart[$product->id]['quantity'] ?? 0);
        if ($quantity >= $product->stock) {
            return redirect()->back()->withErrors(['cart' => 'There is no more stock available for this product.']);
        }

        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image_url' => $product->image_url,
            'quantity' => $quantity + 1,
        ];

        session()->put('cart', $cart);

        return redirect()->back()->with('success', $product->name.' added to your cart.');
    }
}
