<?php

namespace Database\Seeders;

use App\Models\Benefit;
use App\Models\Category;
use App\Models\FarmerFeature;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if (config('admin.email') && config('admin.password')) {
            User::query()->updateOrCreate(
                ['email' => config('admin.email')],
                [
                    'name' => config('admin.name'),
                    'password' => config('admin.password'),
                    'is_admin' => true,
                ],
            );
        }

        $categories = [
            ['name' => 'Leafy Greens', 'slug' => 'leafy-greens', 'icon' => 'leaf', 'description' => 'Vitamin-rich greens', 'sort_order' => 1],
            ['name' => 'Vegetables', 'slug' => 'vegetables', 'icon' => 'vegetable', 'description' => 'Fresh daily harvest', 'sort_order' => 2],
            ['name' => 'Fruits', 'slug' => 'fruits', 'icon' => 'fruit', 'description' => 'Naturally sweet produce', 'sort_order' => 3],
            ['name' => 'Root Vegetables', 'slug' => 'root-vegetables', 'icon' => 'root', 'description' => 'Earthy and nutrient rich', 'sort_order' => 4],
            ['name' => 'Herbs', 'slug' => 'herbs', 'icon' => 'herb', 'description' => 'Aromatic flavors', 'sort_order' => 5],
            ['name' => 'Seasonal Special', 'slug' => 'seasonal-special', 'icon' => 'special', 'description' => 'This week’s fresh picks', 'sort_order' => 6],
        ];

        foreach ($categories as $category) {
            Category::query()->create($category);
        }

        $benefits = [
            ['title' => '100% Organic', 'subtitle' => 'No harmful chemicals.', 'icon' => 'shield', 'sort_order' => 1],
            ['title' => 'Direct from Farmers', 'subtitle' => 'Fair price for farmers.', 'icon' => 'farmer', 'sort_order' => 2],
            ['title' => 'Freshly Harvested', 'subtitle' => 'Daily supply.', 'icon' => 'spark', 'sort_order' => 3],
            ['title' => 'Safe & Secure Payment', 'subtitle' => 'Multiple payment options.', 'icon' => 'credit', 'sort_order' => 4],
            ['title' => 'Fast Delivery', 'subtitle' => 'Across Bangladesh.', 'icon' => 'truck', 'sort_order' => 5],
        ];

        foreach ($benefits as $benefit) {
            Benefit::query()->create($benefit);
        }

        FarmerFeature::query()->create([
            'title' => 'Fresh from our local farms',
            'subtitle' => 'Farmers we trust',
            'image_url' => 'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?auto=format&fit=crop&w=900&q=80',
            'description' => 'We connect households with handpicked, traceable produce from trusted growers across Bangladesh.',
            'cta_label' => 'Meet the farmers',
            'cta_url' => '/shop',
        ]);

        $products = [
            ['category_id' => 1, 'name' => 'Green Spinach', 'slug' => 'green-spinach', 'unit' => 'bundle', 'price' => 42.00, 'compare_price' => 56.00, 'image_url' => 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?auto=format&fit=crop&w=900&q=80', 'badge' => 'New', 'short_description' => 'Tender leaves packed with iron and vitamins.', 'description' => 'Fresh spinach harvested in the morning and packed for clean, nutrient-rich meals.', 'stock' => 60, 'is_featured' => true],
            ['category_id' => 1, 'name' => 'Red Amaranth', 'slug' => 'red-amaranth', 'unit' => 'bundle', 'price' => 38.00, 'compare_price' => 48.00, 'image_url' => 'https://images.unsplash.com/photo-1461354464878-ad92f492a5a0?auto=format&fit=crop&w=900&q=80', 'badge' => 'Popular', 'short_description' => 'Bright and tender leafy greens.', 'description' => 'Highly nutritious greens ideal for curries and soups.', 'stock' => 55, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Farm Tomato', 'slug' => 'farm-tomato', 'unit' => 'kg', 'price' => 52.00, 'compare_price' => 68.00, 'image_url' => 'https://images.unsplash.com/photo-1546094096-0df4bcaaa337?auto=format&fit=crop&w=900&q=80', 'badge' => 'Fresh', 'short_description' => 'Juicy and naturally sweet.', 'description' => 'Picked at peak ripeness for salads, cooking, and sauces.', 'stock' => 72, 'is_featured' => true],
            ['category_id' => 2, 'name' => 'Bottle Gourd', 'slug' => 'bottle-gourd', 'unit' => 'kg', 'price' => 45.00, 'compare_price' => 56.00, 'image_url' => 'https://images.unsplash.com/photo-1518843875459-f738682238a6?auto=format&fit=crop&w=900&q=80', 'badge' => 'Best Seller', 'short_description' => 'Smooth and mild in flavor.', 'description' => 'A versatile family favorite for curries and stir-fries.', 'stock' => 48, 'is_featured' => false],
            ['category_id' => 3, 'name' => 'Sweet Mango', 'slug' => 'sweet-mango', 'unit' => 'kg', 'price' => 110.00, 'compare_price' => 135.00, 'image_url' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=900&q=80', 'badge' => 'Seasonal', 'short_description' => 'Naturally ripened fruit.', 'description' => 'A rich, fragrant fruit full of sweetness and vitamins.', 'stock' => 40, 'is_featured' => true],
            ['category_id' => 4, 'name' => 'Organic Carrot', 'slug' => 'organic-carrot', 'unit' => 'kg', 'price' => 36.00, 'compare_price' => 46.00, 'image_url' => 'https://images.unsplash.com/photo-1447175008436-054170c2e979?auto=format&fit=crop&w=900&q=80', 'badge' => 'Farm Pick', 'short_description' => 'Crunchy and naturally sweet.', 'description' => 'Root vegetables grown in fertile soil with no artificial additives.', 'stock' => 90, 'is_featured' => false],
            ['category_id' => 5, 'name' => 'Fresh Coriander', 'slug' => 'fresh-coriander', 'unit' => 'bundle', 'price' => 24.00, 'compare_price' => 32.00, 'image_url' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=900&q=80', 'badge' => 'Aromatic', 'short_description' => 'Fresh leaves for flavor and aroma.', 'description' => 'Coriander adds a bright, fresh lift to curries and salads.', 'stock' => 80, 'is_featured' => false],
            ['category_id' => 6, 'name' => 'Seasonal Mixed Box', 'slug' => 'seasonal-mixed-box', 'unit' => 'box', 'price' => 890.00, 'compare_price' => 990.00, 'image_url' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=80', 'badge' => 'Value', 'short_description' => 'A curated weekly harvest bundle.', 'description' => 'The best of the season for family meals and healthy routines.', 'stock' => 25, 'is_featured' => true],
        ];

        foreach ($products as $product) {
            Product::query()->create($product);
        }

        foreach ([
            'brand_name' => 'Krishoker Jhuri',
            'tagline' => 'Fresh from Farmers • Pure for You',
            'hero_eyebrow' => 'Organic Vegetables',
            'hero_title' => 'Krishoker Jhuri',
            'hero_subtitle' => 'Fresh, Natural, Healthy',
            'hero_description' => 'Bringing farm-fresh organic vegetables directly from local farmers to your home.',
        ] as $key => $value) {
            SiteSetting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
