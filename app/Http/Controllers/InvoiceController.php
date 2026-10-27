<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SiteSetting;

class InvoiceController extends Controller
{
    public function customer(string $orderNumber)
    {
        $order = Order::query()
            ->with('items.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return $this->invoiceResponse($order);
    }

    public function admin(Order $order)
    {
        $order->load('items.product');

        return $this->invoiceResponse($order);
    }

    private function invoiceResponse(Order $order)
    {
        return response()
            ->view('orders.invoice', [
                'order' => $order,
                'siteSettings' => SiteSetting::values(),
            ])
            ->header('Cache-Control', 'private, no-store, max-age=0')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }
}
