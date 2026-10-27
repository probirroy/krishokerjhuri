@extends('admin.layout')

@section('title', 'Blog posts')
@section('heading', 'Blog posts')

@section('content')
    <div class="admin-page-head">
        <div><h1>Blog posts</h1><p>Create stories for the public journal, and choose when each one is published.</p></div>
        <a class="primary-button" href="{{ route('admin.blog-posts.create') }}">Write a post</a>
    </div>
    <div class="admin-panel">
        @if($posts->isEmpty())
            <div class="journal-empty"><h2>No stories yet</h2><p>Create your first post to start the journal.</p><a class="primary-button" href="{{ route('admin.blog-posts.create') }}">Write a post</a></div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>Story</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td><strong>{{ $post->title }}</strong><small class="admin-table-subtitle">{{ $post->slug }}</small></td>
                                @php($isLive = $post->is_published && (!$post->published_at || $post->published_at->isPast()))
                                <td><span class="blog-status {{ $isLive ? 'is-published' : ($post->is_published ? 'is-scheduled' : 'is-draft') }}">{{ $isLive ? 'Published' : ($post->is_published ? 'Scheduled' : 'Draft') }}</span></td>
                                <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
                                <td class="admin-row-actions">
                                    <a class="secondary-button" href="{{ route('admin.blog-posts.edit', $post) }}">Edit</a>
                                    @if($isLive)
                                        <a class="text-button" href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener">View</a>
                                    @endif
                                    <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this blog post?')">
                                        @csrf @method('DELETE')<button class="danger-button" type="submit">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $posts->links() }}
        @endif
    </div>
@endsection
