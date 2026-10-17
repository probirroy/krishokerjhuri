<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, ?string $slug = null)
    {
        $validated = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ]);
        $query = trim($validated['q'] ?? '');

        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();

        $selectedCategory = null;
        $productsQuery = Product::query()->with('category')->where('is_active', true);

        if ($slug) {
            $selectedCategory = Category::query()->where('slug', $slug)->where('is_active', true)->first();
            if ($selectedCategory) {
                $productsQuery->where('category_id', $selectedCategory->id);
            }
        }

        if ($query !== '') {
            $productsQuery->where(function ($productQuery) use ($query) {
                $like = '%'.$query.'%';
                $productQuery->where('name', 'like', $like)
                    ->orWhere('sku', 'like', $like)
                    ->orWhere('barcode', 'like', $like)
                    ->orWhere('short_description', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', $like));
            });
        }

        $products = $productsQuery->orderBy('name')->paginate(12)->withQueryString();

        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('shop', compact('categories', 'selectedCategory', 'products', 'cartCount', 'siteSettings', 'query'));
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

    public function addToCart(Product $product)
    {
        if ($response = $this->addProductToCart($product)) {
            return $response;
        }

        return redirect()->back()->with('success', __(':name added to your cart.', ['name' => __($product->name)]));
    }

    public function quickBuy(Product $product): RedirectResponse
    {
        if ($response = $this->addProductToCart($product)) {
            return $response;
        }

        return redirect()->route('checkout');
    }

    private function addProductToCart(Product $product): ?RedirectResponse
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

        return null;
    }
}
