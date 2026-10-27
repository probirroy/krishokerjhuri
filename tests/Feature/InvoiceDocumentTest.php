<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvoiceDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_invoice_from_a_signed_order_link(): void
    {
        $order = $this->createOrder();

        $confirmation = $this->get(route('orders.confirmation', $order->order_number))
            ->assertOk()
            ->assertSee('orders/'.$order->order_number.'/invoice?', false);
        $this->assertTrue(Str::contains($confirmation->getContent(), 'signature='));

        $this->get(route('orders.invoice', ['orderNumber' => $order->order_number]))
            ->assertForbidden();

        $signedUrl = URL::signedRoute('orders.invoice', [
            'orderNumber' => $order->order_number,
        ]);

        $response = $this->get($signedUrl)
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertSee('data-order-barcode="KJ-INVOICE-0001"', false)
            ->assertSee('data-product-qr', false)
            ->assertSee(route('products.show', 'invoice-test-product'), false)
            ->assertSee('Invoice Customer')
            ->assertSee('17 Farm Road')
            ->assertSee('৳70.00')
            ->assertSee('৳170.00');
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_only_admins_can_access_admin_invoice_routes(): void
    {
        $order = $this->createOrder();

        $this->get(route('admin.orders.invoice', $order))
            ->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.orders.invoice', $order))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get(route('admin.orders.invoice', $order))
            ->assertOk()
            ->assertSee('data-order-barcode="KJ-INVOICE-0001"', false);
    }

    private function createOrder(): Order
    {
        $category = Category::create([
            'name' => 'Invoice Test Category',
            'slug' => 'invoice-test-category',
            'is_active' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Invoice Test Product',
            'slug' => 'invoice-test-product',
            'unit' => 'kg',
            'price' => 100,
            'stock' => 4,
            'is_active' => true,
        ]);
        $order = Order::create([
            'order_number' => 'KJ-INVOICE-0001',
            'customer_name' => 'Invoice Customer',
            'email' => 'invoice@example.test',
            'phone' => '+8801700000000',
            'address' => '17 Farm Road',
            'district' => 'Dhaka',
            'payment_method' => 'cod',
            'status' => 'pending',
            'subtotal' => 100,
            'shipping' => 70,
            'total' => 170,
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit' => $product->unit,
            'quantity' => 1,
            'unit_price' => $product->price,
            'line_total' => $product->price,
        ]);

        return $order;
    }
}
