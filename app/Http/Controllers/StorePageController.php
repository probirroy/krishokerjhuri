<?php

namespace App\Http\Controllers;

use App\Mail\ContactMessage;
use App\Models\BlogPost;
use App\Models\FarmerFeature;
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
