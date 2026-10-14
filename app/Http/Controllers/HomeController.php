<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use App\Models\Category;
use App\Models\FarmerFeature;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Schema::hasTable('categories')
            ? Category::query()->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        $benefits = Schema::hasTable('benefits')
            ? Benefit::query()->where('is_active', true)->orderBy('sort_order')->get()
            : collect();

        $featuredProducts = Schema::hasTable('products')
            ? Product::query()->with('category')->where('is_active', true)->where('is_featured', true)->orderByDesc('updated_at')->limit(4)->get()
            : collect();

        $farmerFeature = Schema::hasTable('farmer_features')
            ? FarmerFeature::query()->where('is_active', true)->first()
            : null;

        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('home', compact('categories', 'benefits', 'featuredProducts', 'farmerFeature', 'cartCount', 'siteSettings'));
    }
}
