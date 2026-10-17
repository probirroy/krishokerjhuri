<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Category;
use App\Models\FarmerFeature;
use App\Models\Product;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::query()->where('is_active', true)->orderBy('sort_order')->get();
        $benefits = Benefit::query()->where('is_active', true)->orderBy('sort_order')->get();
        $featuredProducts = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('updated_at')
            ->limit(4)
            ->get();
        $farmerFeature = FarmerFeature::query()->where('is_active', true)->first();

        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('home', compact('categories', 'benefits', 'featuredProducts', 'farmerFeature', 'cartCount', 'siteSettings'));
    }
}
