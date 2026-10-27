<?php

namespace Tests\Feature;

use App\Mail\ContactMessage;
use App\Models\BlogPost;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StorePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_pages_are_public_and_navigation_uses_dedicated_routes(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Good food brings us closer to the farm')
            ->assertSee(route('farmers'), false)
            ->assertSee(route('blog.index'), false)
            ->assertSee(route('contact'), false);

        $this->get(route('farmers'))->assertOk()->assertSee('Meet the hands that grow your food');
        $this->get(route('blog.index'))->assertOk()->assertSee('Notes from the field');
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Send a message')
            ->assertSee('hello@krishokerjhuri.com')
            ->assertSee('Dhaka, Bangladesh');
    }

    public function test_only_published_and_due_blog_posts_are_visible(): void
    {
        $published = BlogPost::create([
            'title' => 'A seasonal kitchen',
            'slug' => 'seasonal-kitchen',
            'excerpt' => 'Ideas for this week.',
            'body' => "Fresh produce makes a bright table.\n<script>alert('not run')</script>",
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);
        BlogPost::create([
            'title' => 'A draft story',
            'slug' => 'draft-story',
            'body' => 'Not for the public yet.',
            'is_published' => false,
        ]);
        BlogPost::create([
            'title' => 'A scheduled story',
            'slug' => 'scheduled-story',
            'body' => 'Coming later.',
            'is_published' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('blog.index'))
            ->assertOk()
            ->assertSee($published->title)
            ->assertDontSee('A draft story')
            ->assertDontSee('A scheduled story');

        $this->get(route('blog.show', $published->slug))
            ->assertOk()
            ->assertSee($published->title)
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee('<script>alert', false);
        $this->get(route('blog.show', 'draft-story'))->assertNotFound();
    }

    public function test_admin_can_customize_page_content_and_manage_blog_posts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->put(route('admin.content.update'), [
            'brand_name' => 'Krishoker Jhuri',
            'hero_title' => 'Fresh, local, thoughtful',
            'about_title' => 'A story shaped by the seasons',
            'about_description' => 'A locally managed story for the about page.',
            'about_value_2_title' => 'Careful choices',
            'farmers_page_title' => 'Meet our growing community',
            'farmers_page_description' => 'Information about working with our growers.',
            'blog_page_title' => 'Kitchen notes',
            'blog_page_description' => 'Stories and seasonal ideas.',
            'contact_page_title' => 'Talk to our team',
            'contact_page_description' => 'We are happy to help.',
            'contact_email' => 'team@example.test',
            'contact_phone' => '+880 1700-000000',
            'store_location' => 'Dhaka, Bangladesh',
            'store_hours' => 'Daily, 9am–8pm',
        ])->assertRedirect(route('admin.content'));

        $this->assertSame('A story shaped by the seasons', SiteSetting::values()['about_title']);
        $this->assertSame('Careful choices', SiteSetting::values()['about_value_2_title']);
        $this->assertSame('Talk to our team', SiteSetting::values()['contact_page_title']);
        $this->get(route('about'))->assertSee('A story shaped by the seasons');
        $this->get(route('contact'))->assertSee('+880 1700-000000');

        $this->actingAs($admin)->post(route('admin.blog-posts.store'), [
            'title' => 'Harvest in the kitchen',
            'excerpt' => 'A short seasonal note.',
            'body' => 'Make the most of what is in season.',
            'is_published' => '1',
            'published_at' => now()->subMinute()->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('admin.blog-posts.index'));

        $this->assertDatabaseHas('blog_posts', [
            'slug' => 'harvest-in-the-kitchen',
            'is_published' => true,
        ]);
        $this->get(route('blog.index'))->assertSee('Harvest in the kitchen');

        $existing = BlogPost::create([
            'title' => 'A saved cover',
            'slug' => 'a-saved-cover',
            'body' => 'Keep the current cover when editing text.',
            'image_path' => '/storage/blog/cover.jpg',
            'is_published' => false,
        ]);
        $this->put(route('admin.blog-posts.update', $existing), [
            'title' => $existing->title,
            'excerpt' => 'Updated introduction.',
            'body' => 'Updated copy.',
            'image_url' => '',
            'is_published' => '0',
            'published_at' => '',
        ])->assertRedirect(route('admin.blog-posts.index'));
        $this->assertSame('/storage/blog/cover.jpg', $existing->fresh()->image_path);
    }

    public function test_contact_form_sends_a_message_to_the_admin_configured_address(): void
    {
        Mail::fake();
        SiteSetting::set('contact_email', 'hello@example.test');

        $this->post(route('contact.send'), [
            'name' => 'A Customer',
            'email' => 'customer@example.test',
            'subject' => 'Order question',
            'message' => 'Could you help with my order?',
        ])->assertRedirect(route('contact'))->assertSessionHas('success');

        Mail::assertSent(ContactMessage::class, fn (ContactMessage $mail): bool => $mail->senderEmail === 'customer@example.test'
            && $mail->hasTo('hello@example.test'));
    }
}
