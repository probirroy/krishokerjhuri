<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Support\DeliveryCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $items = $this->currentItems();
        $subtotal = $items->sum(fn ($item) => (float) $item->price * (int) $item->quantity);
        $cartCount = collect(session('cart', []))->sum('quantity');
        $siteSettings = SiteSetting::values();

        return view('cart', compact('items', 'subtotal', 'cartCount', 'siteSettings'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['quantity' => ['required', 'integer', 'min:0']]);
        $cart = session('cart', []);
        $quantity = (int) $request->input('quantity');
        $product = Product::findOrFail($id);
        abort_unless($product->is_active, 404);
        if ($quantity > $product->stock) {
            return redirect()->route('cart')->withErrors(['cart' => 'Requested quantity is greater than available stock.']);
        }

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Your cart was updated.');
    }

    public function remove(string $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        return redirect()->route('cart')->with('success', 'Item removed from cart.');
    }

    public function checkout(Request $request)
    {
        $items = $this->currentItems();
        if ($items->isEmpty()) {
            return redirect()->route('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }
        foreach ($items as $item) {
            if (! $item->is_active || $item->quantity > $item->stock) {
                return redirect()->route('cart')->withErrors(['cart' => 'A product in your cart is no longer available in the requested quantity.']);
            }
        }

        $subtotal = $items->sum(fn ($item) => (float) $item->price * (int) $item->quantity);
        $siteSettings = SiteSetting::values();
        $deliveryCharges = DeliveryCharge::amounts($siteSettings);
        $district = (string) old('district', '');
        $shipping = DeliveryCharge::forDistrict($district, $siteSettings);
        $total = $subtotal + $shipping;
        $cartCount = collect(session('cart', []))->sum('quantity');

        return view('checkout', compact('items', 'subtotal', 'shipping', 'total', 'cartCount', 'siteSettings', 'deliveryCharges', 'district'));
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:180'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:500'],
            'district' => ['required', 'string', 'max:100'],
            'payment_method' => ['required', 'in:cod'],
        ]);
        $data['district'] = trim($data['district']);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $shipping = DeliveryCharge::forDistrict($data['district'], SiteSetting::values());

        $order = DB::transaction(function () use ($data, $cart, $shipping) {
            $products = Product::query()
                ->whereIn('id', array_keys($cart))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $subtotal = 0;

            foreach ($cart as $productId => $cartItem) {
                $product = $products->get($productId);
                $quantity = (int) ($cartItem['quantity'] ?? 0);
                if (! $product || ! $product->is_active || $quantity < 1 || $quantity > $product->stock) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'cart' => 'A product in your cart is no longer available in the requested quantity.',
                    ]);
                }
                $subtotal += (float) $product->price * $quantity;
            }

            $order = Order::create([
                ...$data,
                'user_id' => auth()->id(),
                'order_number' => 'KJ-'.strtoupper(Str::random(12)),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $subtotal + $shipping,
            ]);

            foreach ($cart as $productId => $cartItem) {
                $product = $products->get($productId);
                $quantity = (int) $cartItem['quantity'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'line_total' => $product->price * $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }

            return $order;
        });

        session()->forget('cart');

        return redirect()->route('orders.confirmation', $order->order_number);
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::with('items')->where('order_number', $orderNumber)->firstOrFail();

        return view('orders.confirmation', [
            'order' => $order,
            'siteSettings' => SiteSetting::values(),
            'cartCount' => collect(session('cart', []))->sum('quantity'),
            'invoiceUrl' => URL::signedRoute('orders.invoice', [
                'orderNumber' => $order->order_number,
            ]),
        ]);
    }

    private function currentItems()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return collect();
        }

        $products = Product::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)->map(function ($item, $id) use ($products) {
            $product = $products->get($id);
            if (! $product) {
                return null;
            }
            $product->quantity = (int) ($item['quantity'] ?? 0);
            return $product;
        })->filter()->values();
    }
}
