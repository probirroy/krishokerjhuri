<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Benefit;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\FarmerFeature;
use Database\Seeders\ProductCatalogMetadataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorefrontAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_the_reference_storefront_with_working_product_actions(): void
    {
        $category = Category::create([
            'name' => 'Leafy Greens',
            'slug' => 'leafy-greens',
            'icon' => 'leaf',
            'is_active' => true,
        ]);
        Benefit::create([
            'title' => 'Direct from Farmers',
            'subtitle' => 'Fair prices for farmers',
            'icon' => 'farmer',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Fresh Spinach',
            'slug' => 'fresh-spinach',
            'sku' => 'KJ-HOME-SPINACH',
            'barcode' => '8901234567890',
            'unit' => 'bundle',
            'price' => 45,
            'image_url' => 'https://example.test/spinach.jpg',
            'stock' => 10,
            'is_featured' => true,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('images/brand-wordmark.jpg')
            ->assertSee('images/home-harvest.jpg')
            ->assertSee('Organic Vegetables')
            ->assertSee('reference-category-strip')
            ->assertSee('Leafy Greens')
            ->assertSee('Direct from Farmers')
            ->assertSee('Support Local Farmers')
            ->assertSee('Popular Categories')
            ->assertSee('Fresh Spinach')
            ->assertSee('KJ-HOME-SPINACH')
            ->assertSee('8901234567890')
            ->assertSee(route('products.show', $product->slug), false)
            ->assertSee(route('products.add_to_cart', $product), false)
            ->assertSee(route('products.quick_buy', $product), false);
    }

    public function test_customer_can_switch_between_english_and_bangla(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('Home');

        $this->post('/language/bn')->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="bn">', false)
            ->assertSee('হোম')
            ->assertSee('কৃষকের ঝুড়ি')
            ->assertSee('বিশুদ্ধ জৈব টাটকা')
            ->assertDontSee('Fresh from Farmers • Pure for You')
            ->assertSee('English');

        $this->post('/language/en')->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('Home');

        $this->post('/language/fr')->assertNotFound();
    }

    public function test_bangla_localizes_catalog_copy_prices_and_validation_errors(): void
    {
        $category = Category::create([
            'name' => 'Leafy Greens',
            'slug' => 'leafy-greens',
            'icon' => 'leaf',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Green Spinach',
            'slug' => 'green-spinach',
            'sku' => 'KJ-GREEN-SPINACH',
            'barcode' => '8901234567890',
            'unit' => 'bundle',
            'price' => 45,
            'short_description' => 'Tender leafy greens for everyday meals, selected for freshness and packed by the bundle.',
            'description' => "Bring fresh green spinach to your kitchen for quick, nourishing meals. Its tender leaves work well in dal, curries, omelettes, and simple sautés. Rinse thoroughly before cooking and season to taste.\n\nSold by the bundle. Actual appearance may vary with the day’s harvest.",
            'seo_title' => 'Fresh Green Spinach Bundle | Krishoker Jhuri',
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->post('/language/bn')->assertRedirect();

        $this->get('/shop')
            ->assertOk()
            ->assertSee('টাটকা পালং শাক')
            ->assertSee('৳৪৫.০০');

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('টাটকা পালং শাকের আঁটি | কৃষকের ঝুড়ি')
            ->assertSee('দ্রুত ও পুষ্টিকর খাবারের জন্য রান্নাঘরে আনুন টাটকা পালং শাক।')
            ->assertSee('KJ-GREEN-SPINACH')
            ->assertSee('8901234567890')
            ->assertSee('৳৪৫.০০');

        $this->withSession(['cart' => [$product->id => ['quantity' => 1]]])
            ->from('/cart')
            ->post('/cart/'.$product->id.'/update', ['quantity' => 'not-a-number'])
            ->assertRedirect('/cart')
            ->assertSessionHasErrors('quantity');

        $this->get('/cart')
            ->assertOk()
            ->assertSee('পরিমাণ অবশ্যই পূর্ণসংখ্যা হতে হবে।')
            ->assertSee('KJ-GREEN-SPINACH')
            ->assertSee('8901234567890');

        $this->get('/checkout')
            ->assertOk()
            ->assertSee('KJ-GREEN-SPINACH')
            ->assertSee('8901234567890');
    }

    public function test_storefront_search_finds_active_products_by_name_sku_barcode_and_description(): void
    {
        $category = Category::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
            'icon' => 'vegetable',
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Farm Tomato',
            'slug' => 'farm-tomato',
            'sku' => 'KJ-TOMATO-001',
            'barcode' => '8901234567890',
            'unit' => 'kg',
            'price' => 52,
            'short_description' => 'Fresh tomatoes for cooking',
            'stock' => 10,
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Bottle Gourd',
            'slug' => 'bottle-gourd',
            'sku' => 'KJ-GOURD-001',
            'unit' => 'kg',
            'price' => 45,
            'short_description' => 'Fresh gourd for curries',
            'stock' => 10,
            'is_active' => true,
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Hidden Tomato',
            'slug' => 'hidden-tomato',
            'sku' => 'KJ-HIDDEN-TOMATO',
            'unit' => 'kg',
            'price' => 30,
            'stock' => 10,
            'is_active' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('header-search')
            ->assertSee('Search products');

        $this->get('/shop?q=tomato')
            ->assertOk()
            ->assertSee('Search results for tomato')
            ->assertSee('Farm Tomato')
            ->assertDontSee('Bottle Gourd')
            ->assertDontSee('Hidden Tomato')
            ->assertSee('name="q" value="tomato"', false);

        $this->get('/shop?q=KJ-TOMATO-001')
            ->assertOk()
            ->assertSee('Farm Tomato')
            ->assertDontSee('Bottle Gourd');

        $this->get('/shop?q=8901234567890')
            ->assertOk()
            ->assertSee('Farm Tomato')
            ->assertDontSee('Bottle Gourd');

        $this->get('/shop?q=curries')
            ->assertOk()
            ->assertSee('Bottle Gourd')
            ->assertDontSee('Farm Tomato');

        $this->get('/shop?q=Vegetables')
            ->assertOk()
            ->assertSee('Farm Tomato')
            ->assertSee('Bottle Gourd');

        $this->get('/shop?q=not-a-real-product')
            ->assertOk()
            ->assertSee('No products matched your search.');
    }

    public function test_admin_area_has_a_separate_login_and_requires_admin_role(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();

        $admin = User::factory()->create([
            'email' => 'admin@test.local',
            'password' => 'test-password',
            'is_admin' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@test.local',
            'password' => 'test-password',
        ])->assertRedirect('/admin');
        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_non_admin_accounts_cannot_access_the_admin_panel(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->post('/admin/logout')->assertForbidden();
    }

    public function test_customer_can_register_with_email_and_sign_in_then_sign_out(): void
    {
        $this->get('/register')->assertOk()->assertSee('Mobile number')->assertDontSee('Administrator sign in')->assertDontSee('/admin/login');
        $this->get('/login')->assertOk()->assertSee('Email or mobile number')->assertDontSee('Administrator sign in')->assertDontSee('/admin/login');

        $this->post('/register', [
            'name' => 'Email Customer',
            'email' => 'email-customer@example.test',
            'password' => 'customer-password',
            'password_confirmation' => 'customer-password',
            'is_admin' => 1,
        ])->assertRedirect('/');

        $customer = User::where('email', 'email-customer@example.test')->firstOrFail();
        $this->assertFalse($customer->is_admin);
        $this->post('/logout')->assertRedirect('/');

        $this->post('/login', [
            'identifier' => 'email-customer@example.test',
            'password' => 'customer-password',
        ])->assertRedirect('/');
        $this->assertAuthenticatedAs($customer);

        $this->post('/admin/login', [
            'email' => 'email-customer@example.test',
            'password' => 'customer-password',
        ])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_customer_can_register_and_sign_in_using_mobile_number(): void
    {
        $this->post('/register', [
            'name' => 'Mobile Customer',
            'mobile' => '+8801700000000',
            'password' => 'customer-password',
            'password_confirmation' => 'customer-password',
        ])->assertRedirect('/');

        $customer = User::where('mobile', '+8801700000000')->firstOrFail();
        $this->assertNull($customer->email);
        $this->post('/logout')->assertRedirect('/');
        $this->post('/login', [
            'identifier' => '+8801700000000',
            'password' => 'customer-password',
        ])->assertRedirect('/');
        $this->assertAuthenticatedAs($customer);
    }

    public function test_admin_can_create_a_product_and_edit_storefront_content(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Fruit', 'slug' => 'fruit', 'is_active' => true]);

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Sweet Guava',
            'sku' => 'FRUIT-GUAVA-01',
            'barcode' => '8901234567890',
            'unit' => 'kg',
            'price' => 90,
            'stock' => 12,
            'image_url' => 'https://example.test/guava.jpg',
            'short_description' => 'A fragrant, sweet guava for snacks and fruit bowls.',
            'description' => 'Select ripe guavas for everyday enjoyment.',
            'seo_title' => 'Fresh Sweet Guava | Krishoker Jhuri',
            'seo_description' => 'Order sweet guava online.',
            'seo_keywords' => 'sweet guava, fresh fruit',
            'is_active' => 1,
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'slug' => 'sweet-guava',
            'stock' => 12,
            'sku' => 'FRUIT-GUAVA-01',
            'barcode' => '8901234567890',
            'seo_title' => 'Fresh Sweet Guava | Krishoker Jhuri',
        ]);

        $product = Product::where('slug', 'sweet-guava')->firstOrFail();
        $this->actingAs($admin)
            ->get('/admin/products/'.$product->id.'/edit')
            ->assertOk()
            ->assertSee('Barcode value')
            ->assertSee('Search &amp; social preview', false)
            ->assertSee('FRUIT-GUAVA-01');

        $this->actingAs($admin)->put('/admin/products/'.$product->id, [
            'category_id' => $category->id,
            'name' => 'Sweet Guava',
            'sku' => 'FRUIT-GUAVA-01',
            'barcode' => '8901234567890',
            'unit' => 'kg',
            'price' => 90,
            'stock' => 12,
            'image_url' => 'https://example.test/guava.jpg',
            'short_description' => 'A fragrant, sweet guava for snacks and fruit bowls.',
            'description' => 'Select ripe guavas for everyday enjoyment.',
            'seo_title' => 'Fresh Sweet Guava | Krishoker Jhuri',
            'seo_description' => 'Order sweet guava online.',
            'seo_keywords' => 'sweet guava, fresh fruit',
            'is_active' => 1,
        ])->assertRedirect('/admin/products');

        $this->get('/products/sweet-guava')
            ->assertOk()
            ->assertSee('<meta name="description" content="Order sweet guava online.">', false)
            ->assertSee('<meta name="keywords" content="sweet guava, fresh fruit">', false)
            ->assertSee('"sku":"FRUIT-GUAVA-01"', false)
            ->assertSee('"gtin":"8901234567890"', false);

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Sweet Guava Select',
            'unit' => 'kg',
            'price' => 95,
            'stock' => 8,
            'barcode' => '8901234567890',
            'is_active' => 1,
        ])->assertSessionHasErrors('barcode');

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Sweet Guava Select',
            'unit' => 'kg',
            'price' => 95,
            'stock' => 8,
            'is_active' => 1,
        ])->assertRedirect('/admin/products');
        $this->assertDatabaseHas('products', ['slug' => 'sweet-guava-select', 'sku' => 'KJ-SWEET-GUAVA-SELECT']);

        FarmerFeature::create([
            'title' => 'Old title',
            'subtitle' => 'Our farmers',
            'description' => 'Old copy',
            'cta_label' => 'Learn more',
        ]);

        $this->put('/admin/content', [
            'brand_name' => 'Jhuri Market',
            'tagline' => 'A new tagline',
            'hero_title' => 'Fresh Harvest',
            'hero_subtitle' => 'Straight from the fields',
            'promo_title' => 'Meet our growers',
            'promo_subtitle' => 'Farmer stories',
            'promo_description' => 'From local farms.',
            'promo_cta_label' => 'Meet them',
            'social_facebook_url' => 'https://www.facebook.com/krishokerjhuri',
            'social_instagram_url' => 'https://www.instagram.com/krishokerjhuri',
            'social_youtube_url' => 'https://www.youtube.com/@krishokerjhuri',
        ])->assertRedirect('/admin/content');

        $this->assertDatabaseHas('site_settings', ['key' => 'brand_name', 'value' => 'Jhuri Market']);
        $this->assertDatabaseHas('site_settings', ['key' => 'social_facebook_url', 'value' => 'https://www.facebook.com/krishokerjhuri']);
        $this->assertSame('Meet our growers', FarmerFeature::first()->title);
        $this->get('/')
            ->assertOk()
            ->assertSee('Jhuri Market')
            ->assertSee('&copy; '.now()->year.' Jhuri Market', false)
            ->assertSee('<a href="https://www.facebook.com/krishokerjhuri" target="_blank" rel="noopener noreferrer" aria-label="Facebook"', false)
            ->assertSee('<a href="https://www.instagram.com/krishokerjhuri"', false)
            ->assertSee('<a href="https://www.youtube.com/@krishokerjhuri"', false);

        $this->from('/admin/content')->put('/admin/content', [
            'brand_name' => 'Jhuri Market',
            'hero_title' => 'Fresh Harvest',
            'social_facebook_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('social_facebook_url');
    }

    public function test_demo_catalog_metadata_seeder_adds_professional_copy_and_sku(): void
    {
        $category = Category::create(['name' => 'Leafy Greens', 'slug' => 'leafy-greens', 'is_active' => true]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Green Spinach',
            'slug' => 'green-spinach',
            'unit' => 'bundle',
            'price' => 42,
            'short_description' => 'Tender leaves packed with iron and vitamins.',
            'description' => 'Fresh spinach harvested in the morning and packed for clean, nutrient-rich meals.',
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->seed(ProductCatalogMetadataSeeder::class);

        $product = Product::where('slug', 'green-spinach')->firstOrFail();
        $this->assertSame('KJ-'.str_pad((string) $product->id, 6, '0', STR_PAD_LEFT), $product->sku);
        $this->assertStringContainsString('omelettes', $product->description);
        $this->assertSame('Fresh Green Spinach Bundle | Krishoker Jhuri', $product->seo_title);
        $this->assertNotNull($product->seo_description);
    }

    public function test_admin_can_grant_and_revoke_access_but_cannot_remove_their_own_access(): void
    {
        $admin = User::factory()->create([
            'email' => 'first-admin@example.test',
            'is_admin' => true,
        ]);
        $customer = User::factory()->create([
            'email' => 'new-admin@example.test',
            'is_admin' => false,
        ]);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Administrator access');

        $this->put('/admin/users/'.$customer->id.'/admin-access', ['is_admin' => 1])
            ->assertRedirect('/admin/users');
        $this->assertTrue($customer->fresh()->is_admin);

        $this->put('/admin/users/'.$admin->id.'/admin-access', ['is_admin' => 0])
            ->assertSessionHasErrors('is_admin');
        $this->assertTrue($admin->fresh()->is_admin);

        $this->put('/admin/users/'.$customer->id.'/admin-access', ['is_admin' => 0])
            ->assertRedirect('/admin/users');
        $this->assertFalse($customer->fresh()->is_admin);

        $this->put('/admin/users/'.$customer->id.'/admin-access', ['is_admin' => 1])
            ->assertRedirect('/admin/users');
        $customer->refresh();
        $this->put('/admin/users/'.$admin->id.'/admin-access', ['is_admin' => 0])
            ->assertSessionHasErrors('is_admin');
        $this->assertTrue($admin->fresh()->is_admin);
        $this->assertTrue($customer->fresh()->is_admin);

        $this->actingAs($customer)
            ->put('/admin/users/'.$admin->id.'/admin-access', ['is_admin' => 0])
            ->assertRedirect('/admin/users');
        $customer->refresh();
        $this->assertFalse($admin->fresh()->is_admin);

        $this->put('/admin/users/'.$customer->id.'/admin-access', ['is_admin' => 0])
            ->assertSessionHasErrors('is_admin');
        $this->assertTrue($customer->fresh()->is_admin);
    }

    public function test_admin_product_image_upload_is_stored_on_the_public_disk(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Greens', 'slug' => 'greens', 'is_active' => true]);

        $this->actingAs($admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Fresh Spinach',
            'unit' => 'bundle',
            'price' => 30,
            'stock' => 7,
            'image' => UploadedFile::fake()->create('spinach.png', 20, 'image/png'),
            'is_active' => 1,
        ])->assertRedirect('/admin/products');

        $product = Product::where('slug', 'fresh-spinach')->firstOrFail();
        $this->assertStringStartsWith('/storage/uploads/', $product->image_url);
        Storage::disk('public')->assertExists(substr($product->image_url, strlen('/storage/')));
    }

    public function test_product_detail_and_cash_on_delivery_checkout_work(): void
    {
        $category = Category::create([
            'name' => 'Vegetables',
            'slug' => 'vegetables',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Tomato',
            'slug' => 'tomato',
            'unit' => 'kg',
            'price' => 50,
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->get('/products/tomato')
            ->assertOk()
            ->assertSee('Tomato')
            ->assertSee(route('products.quick_buy', $product), false);

        $this->withSession(['cart' => []])
            ->post(route('products.quick_buy', $product))
            ->assertRedirect(route('checkout'))
            ->assertSessionHas('cart.'.$product->id.'.quantity', 1);

        $this->get('/checkout')->assertOk()->assertSee('Tomato');

        $this->from('/products/tomato')
            ->withSession(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 5]]])
            ->post(route('products.quick_buy', $product))
            ->assertRedirect('/products/tomato')
            ->assertSessionHasErrors('cart');

        $this->withSession(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 2]]])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Cash on delivery');

        $this->withSession(['cart' => [$product->id => ['id' => $product->id, 'quantity' => 2]]])
            ->post('/checkout', [
                'customer_name' => 'Test Customer',
                'email' => 'customer@example.test',
                'phone' => '01700000000',
                'address' => '12 Farm Road',
                'district' => 'Dhaka',
                'payment_method' => 'cod',
            ])
            ->assertRedirect();

        $order = Order::with('items')->firstOrFail();
        $this->assertSame(175.0, (float) $order->total);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertSame('Tomato', $order->items->first()->product_name);
        $this->get('/orders/'.$order->order_number)->assertOk()->assertSee($order->order_number);

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)
            ->put('/admin/orders/'.$order->id, ['status' => 'cancelled'])
            ->assertRedirect('/admin/orders');
        $this->assertSame(5, $product->fresh()->stock);

        $this->put('/admin/orders/'.$order->id, ['status' => 'processing'])
            ->assertRedirect('/admin/orders');
        $this->assertSame(3, $product->fresh()->stock);
    }
}
