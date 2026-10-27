<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        return view('admin.blog.index', [
            'posts' => BlogPost::query()->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog.form', ['post' => new BlogPost()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['image_path'] = $this->imagePath($request, $data['image_url'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?: now()) : $data['published_at'];
        unset($data['image'], $data['image_url']);

        BlogPost::create($data);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog_post): View
    {
        return view('admin.blog.form', ['post' => $blog_post]);
    }

    public function update(Request $request, BlogPost $blog_post): RedirectResponse
    {
        $data = $this->validatedData($request);
        if ($blog_post->title !== $data['title']) {
            $data['slug'] = $this->uniqueSlug($data['title'], $blog_post->id);
        }
        $data['image_path'] = $this->imagePath($request, $data['image_url'] ?? $blog_post->image_path);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?: now()) : $data['published_at'];
        unset($data['image'], $data['image_url']);

        $blog_post->update($data);

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog_post): RedirectResponse
    {
        $blog_post->delete();

        return redirect()->route('admin.blog-posts.index')->with('success', 'Blog post deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'body' => ['required', 'string', 'max:50000'],
            'image' => ['nullable', 'image', 'max:6144'],
            'image_url' => ['nullable', 'url:http,https', 'max:2048'],
            'published_at' => ['nullable', 'date'],
        ]);
    }

    private function imagePath(Request $request, ?string $fallback): ?string
    {
        if ($request->hasFile('image')) {
            return '/storage/'.$request->file('image')->store('blog', 'public');
        }

        return $request->filled('image_url') ? $request->input('image_url') : $fallback;
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'story';
        $slug = $base;
        $suffix = 2;

        while (BlogPost::query()->where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
