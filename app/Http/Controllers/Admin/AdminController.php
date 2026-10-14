<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Category;
use App\Models\FarmerFeature;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'orderCount' => Order::count(),
            'pendingOrderCount' => Order::where('status', 'pending')->count(),
            'recentOrders' => Order::latest()->limit(8)->get(),
        ]);
    }

    public function products()
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->paginate(20),
        ]);
    }

    public function createProduct()
    {
        return view('admin.products.form', ['product' => new Product(), 'categories' => Category::orderBy('name')->get()]);
    }

    public function storeProduct(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['image_url'] = $this->imagePath($request, 'image', $data['image_url'] ?? null);
        unset($data['image']);
        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get()]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product);
        if ($product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }
        $data['image_url'] = $this->imagePath($request, 'image', $data['image_url'] ?? $product->image_url);
        unset($data['image']);
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function categories()
    {
        return view('admin.categories', ['categories' => Category::withCount('products')->orderBy('sort_order')->get()]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', 'alpha_dash', 'unique:categories,slug'],
            'icon' => ['nullable', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['slug'] = $data['slug'] ?: $this->uniqueSlug($data['name'], null, Category::class);
        $data['is_active'] = $request->boolean('is_active');
        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', 'alpha_dash', Rule::unique('categories', 'slug')->ignore($category->id)],
            'icon' => ['nullable', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:1000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated.');
    }

    public function deleteCategory(Category $category)
    {
        if ($category->products()->exists()) {
            return redirect()->route('admin.categories.index')->withErrors(['category' => 'Move or delete this category’s products before removing it.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted.');
    }

    public function content()
    {
        return view('admin.content', [
            'settings' => SiteSetting::values(),
            'benefits' => Benefit::orderBy('sort_order')->get(),
            'farmerFeature' => FarmerFeature::first(),
        ]);
    }

    public function updateContent(Request $request)
    {
        $data = $request->validate([
            'brand_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'hero_eyebrow' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:180'],
            'hero_subtitle' => ['nullable', 'string', 'max:180'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'hero_button_label' => ['nullable', 'string', 'max:80'],
            'hero_badge' => ['nullable', 'string', 'max:100'],
            'featured_eyebrow' => ['nullable', 'string', 'max:120'],
            'featured_title' => ['nullable', 'string', 'max:180'],
            'contact_email' => ['nullable', 'email', 'max:180'],
            'store_location' => ['nullable', 'string', 'max:180'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'hero_image' => ['nullable', 'image', 'max:6144'],
            'hero_image_url' => ['nullable', 'url', 'max:2048'],
            'promo_title' => ['nullable', 'string', 'max:180'],
            'promo_subtitle' => ['nullable', 'string', 'max:180'],
            'promo_description' => ['nullable', 'string', 'max:1500'],
            'promo_image' => ['nullable', 'image', 'max:6144'],
            'promo_cta_label' => ['nullable', 'string', 'max:80'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->imagePath($request, 'logo');
        }
        if ($request->hasFile('hero_image')) {
            $data['hero_image_path'] = $this->imagePath($request, 'hero_image');
        } elseif (! empty($data['hero_image_url'])) {
            $data['hero_image_path'] = $data['hero_image_url'];
        }
        if ($request->hasFile('promo_image')) {
            $data['promo_image_path'] = $this->imagePath($request, 'promo_image');
        }

        foreach (['logo', 'hero_image', 'promo_image'] as $field) {
            unset($data[$field]);
        }
        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value ?? '');
        }

        $benefitData = $request->validate([
            'benefits' => ['nullable', 'array'],
            'benefits.*.title' => ['required_with:benefits', 'string', 'max:120'],
            'benefits.*.subtitle' => ['nullable', 'string', 'max:180'],
            'benefits.*.is_active' => ['nullable', 'boolean'],
        ]);
        foreach ($benefitData['benefits'] ?? [] as $id => $benefit) {
            Benefit::whereKey($id)->update([
                'title' => $benefit['title'],
                'subtitle' => $benefit['subtitle'] ?? '',
                'is_active' => $request->boolean("benefits.$id.is_active"),
            ]);
        }

        $farmer = FarmerFeature::firstOrNew();
        $farmer->fill([
            'title' => $data['promo_title'] ?? '',
            'subtitle' => $data['promo_subtitle'] ?? '',
            'description' => $data['promo_description'] ?? '',
            'cta_label' => $data['promo_cta_label'] ?? 'Learn More',
            'image_url' => $data['promo_image_path'] ?? $farmer->image_url,
            'is_active' => true,
        ])->save();

        return redirect()->route('admin.content')->with('success', 'Store content updated.');
    }

    public function orders()
    {
        return view('admin.orders', ['orders' => Order::latest()->paginate(20)]);
    }

    public function users()
    {
        return view('admin.users', [
            'users' => User::query()->orderByDesc('is_admin')->orderBy('name')->paginate(30),
            'adminCount' => User::where('is_admin', true)->count(),
        ]);
    }

    public function updateAdminAccess(Request $request, User $user)
    {
        $data = $request->validate([
            'is_admin' => ['required', 'boolean'],
        ]);
        $grantAccess = (bool) $data['is_admin'];

        if ($grantAccess && ! $user->email) {
            throw ValidationException::withMessages([
                'is_admin' => 'Add an email address to this account before granting admin access. Admin sign-in uses email.',
            ]);
        }

        if (! $grantAccess && $request->user()->is($user)) {
            throw ValidationException::withMessages([
                'is_admin' => 'You cannot remove your own admin access while signed in.',
            ]);
        }

        DB::transaction(function () use ($user, $grantAccess) {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            if ($lockedUser->is_admin && ! $grantAccess) {
                $adminCount = User::query()->where('is_admin', true)->lockForUpdate()->count();
                if ($adminCount <= 1) {
                    throw ValidationException::withMessages([
                        'is_admin' => 'The last administrator cannot be removed.',
                    ]);
                }
            }

            $lockedUser->update(['is_admin' => $grantAccess]);
        });

        return redirect()->route('admin.users.index')->with(
            'success',
            $grantAccess ? 'Administrator access granted.' : 'Administrator access removed.',
        );
    }

    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
        ]);

        DB::transaction(function () use ($order, $data) {
            $order = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($order->status !== $data['status']) {
                if ($data['status'] === 'cancelled') {
                    if (in_array($order->status, ['shipped', 'delivered'], true)) {
                        throw ValidationException::withMessages(['status' => 'Shipped or delivered orders cannot be cancelled.']);
                    }
                    foreach ($order->items as $item) {
                        Product::query()->whereKey($item->product_id)->increment('stock', $item->quantity);
                    }
                } elseif ($order->status === 'cancelled') {
                    foreach ($order->items as $item) {
                        $product = Product::query()->whereKey($item->product_id)->lockForUpdate()->first();
                        if (! $product || $product->stock < $item->quantity) {
                            throw ValidationException::withMessages(['status' => 'The products in this cancelled order are no longer in stock.']);
                        }
                    }
                    foreach ($order->items as $item) {
                        Product::query()->whereKey($item->product_id)->decrement('stock', $item->quantity);
                    }
                }
                $order->update($data);
            }
        });

        return redirect()->route('admin.orders')->with('success', 'Order status updated.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'unit' => ['required', 'string', 'max:40'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'image' => ['nullable', 'image', 'max:6144'],
            'image_url' => ['nullable', 'url', 'max:255'],
            'badge' => ['nullable', 'string', 'max:80'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function imagePath(Request $request, string $field, ?string $fallback = null): ?string
    {
        if ($request->hasFile($field)) {
            $path = $request->file($field)->store('uploads', 'public');
            return '/storage/'.$path;
        }

        return $request->input('image_url', $fallback);
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null, string $model = Product::class): string
    {
        $slug = str($name)->slug()->toString();
        $slug = $slug !== '' ? $slug : strtolower(class_basename($model));
        $candidate = $slug;
        $suffix = 2;

        while ($model::where('slug', $candidate)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $candidate = $slug.'-'.$suffix++;
        }

        return $candidate;
    }
}
