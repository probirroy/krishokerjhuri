@extends('admin.layout')

@section('title', $post->exists ? 'Edit blog post' : 'Write a blog post')
@section('heading', 'Blog posts')

@section('content')
    <div class="admin-page-head">
        <div><h1>{{ $post->exists ? 'Edit story' : 'Write a story' }}</h1><p>Keep articles helpful, accurate, and grounded in your experience.</p></div>
        <a class="secondary-button" href="{{ route('admin.blog-posts.index') }}">← Back to blog posts</a>
    </div>
    <form class="admin-panel admin-form blog-edit-form" action="{{ $post->exists ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if($post->exists) @method('PUT') @endif
        <div class="form-grid">
            <label class="full-field">Story title<input name="title" value="{{ old('title', $post->title) }}" maxlength="180" required></label>
            <label class="full-field">Short introduction<textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea><small>Shown on the journal card and in search previews.</small></label>
            <label class="full-field">Story content<textarea name="body" rows="16" maxlength="50000" required>{{ old('body', $post->body) }}</textarea><small>Plain text with line breaks. HTML is not accepted and will be displayed safely.</small></label>
            <label>Cover image<input type="file" name="image" accept="image/*"><small>JPG, PNG, or WebP · max 6 MB</small></label>
            <label>Or image URL<input type="url" name="image_url" value="{{ old('image_url', str_starts_with($post->image_path ?? '', 'http') ? $post->image_path : '') }}" placeholder="https://…"></label>
            @if($post->image_path)<div class="logo-preview"><span>Current cover</span><img src="{{ $post->image_path }}" alt="Current blog cover"></div>@endif
            <label>Publish date<input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"><small>Leave blank to publish immediately when publishing is enabled.</small></label>
        </div>
        <label class="check-row"><input type="hidden" name="is_published" value="0"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))> Publish this story</label>
        <button type="submit" class="primary-button">{{ $post->exists ? 'Save changes' : 'Create story' }}</button>
    </form>
@endsection
