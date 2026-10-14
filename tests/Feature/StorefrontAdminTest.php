<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Benefit;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\FarmerFeature;
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
            ->assertSee(route('products.show', $product->slug), false)
            ->assertSee(route('products.add_to_cart', $product), false);
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
            'unit' => 'kg',
            'price' => 90,
            'stock' => 12,
            'image_url' => 'https://example.test/guava.jpg',
            'is_active' => 1,
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', ['slug' => 'sweet-guava', 'stock' => 12]);

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
        ])->assertRedirect('/admin/content');

        $this->assertDatabaseHas('site_settings', ['key' => 'brand_name', 'value' => 'Jhuri Market']);
        $this->assertSame('Meet our growers', FarmerFeature::first()->title);
        $this->get('/')->assertOk()->assertSee('Jhuri Market');
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

        $this->get('/products/tomato')->assertOk()->assertSee('Tomato');
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
