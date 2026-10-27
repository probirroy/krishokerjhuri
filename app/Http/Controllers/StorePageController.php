<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\BlogPost;
use App\Models\FarmerFeature;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class StorePageController extends Controller
{
    public function about(): View
    {
        return $this->page('pages.about');
    }

    public function farmers(): View
    {
        return view('pages.farmers', $this->pageData() + [
            'farmerFeature' => FarmerFeature::query()->where('is_active', true)->first(),
        ]);
    }

    public function blog(): View
    {
        return view('pages.blog-index', $this->pageData() + [
            'posts' => BlogPost::published()->latest('published_at')->latest('created_at')->paginate(9),
        ]);
    }

    public function article(string $slug): View
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return view('pages.blog-show', $this->pageData() + ['post' => $post]);
    }

    public function contact(): View
    {
        return $this->page('pages.contact');
    }

    public function terms(): View
    {
        return $this->policy('Terms and conditions', 'Shopping with us', [
            ['title' => 'Placing an order', 'body' => 'An order is a request to purchase the products in your cart. We will use the contact and delivery details you provide to process it and get in touch if we have a question.'],
            ['title' => 'Products and availability', 'body' => 'Products, prices, and availability may change as the harvest and stock change. If an item in an order is unavailable, please contact us so we can discuss the next steps.'],
            ['title' => 'Payment and delivery', 'body' => 'Cash on delivery is the payment method currently offered at checkout. Delivery charges are shown during checkout and depend on the delivery district.'],
            ['title' => 'Questions', 'body' => 'If you have a question about an order or these terms, please contact our team.'],
        ]);
    }

    public function returns(): View
    {
        return $this->policy('Returns and order issues', 'Here to help', [
            ['title' => 'Something not right?', 'body' => 'Contact us with your order number and a description of the issue. For fresh produce, please get in touch as soon as you can so our team can understand what happened.'],
            ['title' => 'Order changes', 'body' => 'Orders are prepared for delivery, so we cannot guarantee a change or cancellation after an order has been placed. Contact us as soon as possible and we will let you know what can be done.'],
            ['title' => 'Getting in touch', 'body' => 'Use the contact form or the email address and phone number listed on our contact page. We will review your request and reply with the available next steps.'],
        ]);
    }

    public function support(): View
    {
        return $this->policy('Support policy', 'We are here to help', [
            ['title' => 'Order support', 'body' => 'For help with an order, include your order number and the contact details used at checkout so our team can identify the order.'],
            ['title' => 'Product and delivery questions', 'body' => 'Contact us with your question and any details that will help us understand it. Our contact page lists the current ways to reach the store.'],
            ['title' => 'Farmer partnerships', 'body' => 'Local growers interested in working with us are welcome to tell us about their farm and seasonal produce through the contact form.'],
        ]);
    }

    public function privacy(): View
    {
        return $this->policy('Privacy policy', 'Your information', [
            ['title' => 'Information you provide', 'body' => 'Depending on how you use the store, you may provide account details, delivery and contact information, and messages through the contact form.'],
            ['title' => 'How information is used', 'body' => 'The store uses the information you provide to manage your account, process and deliver orders, respond to messages, and operate the website.'],
            ['title' => 'Questions about your information', 'body' => 'For questions about information associated with your account or an order, contact us using the details on our contact page.'],
        ]);
    }

    public function orders(Request $request): View
    {
        abort_if($request->user()->is_admin, 403);

        return view('orders.index', $this->pageData() + [
            'orders' => Order::query()
                ->where('user_id', $request->user()->id)
                ->with('items')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
        ]);
        $recipient = SiteSetting::values()['contact_email'] ?? 'hello@krishokerjhuri.com';

        if (! $recipient) {
            return back()->withInput()->withErrors(['contact' => 'The store has not configured a contact email yet.']);
        }

        Mail::to($recipient)->send(new ContactMessage(
            $data['name'],
            $data['email'],
            $data['subject'],
            $data['message'],
        ));

        return redirect()->route('contact')->with('success', 'Thank you for reaching out. Your message has been sent.');
    }

    private function page(string $view): View
    {
        return view($view, $this->pageData());
    }

    private function policy(string $title, string $eyebrow, array $sections): View
    {
        return view('pages.policy', $this->pageData() + [
            'policyTitle' => $title,
            'policyEyebrow' => $eyebrow,
            'policySections' => $sections,
        ]);
    }

    private function pageData(): array
    {
        $siteSettings = SiteSetting::values();
        $siteSettings += [
            'contact_email' => 'hello@krishokerjhuri.com',
            'store_location' => 'Dhaka, Bangladesh',
            'store_hours' => 'Every day, 9:00 AM – 8:00 PM',
        ];

        return [
            'siteSettings' => $siteSettings,
            'cartCount' => collect(session('cart', []))->sum('quantity'),
        ];
    }
}
