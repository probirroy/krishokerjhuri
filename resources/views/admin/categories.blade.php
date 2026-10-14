@extends('admin.layout')

@section('title', 'Categories')
@section('heading', 'Categories')

@section('content')
    <div class="admin-page-head"><div><h1>Categories</h1><p>Control the product categories shown in your shop.</p></div></div>
    <div class="category-admin-grid">
        <section class="admin-panel">
            <div class="panel-head"><div><h2>Existing categories</h2><p>Edit the details inline.</p></div></div>
            @forelse($categories as $category)
                <form class="category-edit-row" action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-grid">
                        <label>Name<input name="name" value="{{ $category->name }}" required></label>
                        <label>Slug<input name="slug" value="{{ $category->slug }}" required></label>
                        <label>Icon key<input name="icon" value="{{ $category->icon }}"></label>
                        <label>Order<input type="number" min="0" name="sort_order" value="{{ $category->sort_order }}"></label>
                        <label class="full-field">Description<input name="description" value="{{ $category->description }}"></label>
                    </div>
                    <div class="form-options"><label class="check-row"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($category->is_active)> Active · {{ $category->products_count }} products</label><button class="secondary-button small" type="submit">Save</button></div>
                </form>
            @empty
                <div class="admin-empty">No categories have been created.</div>
            @endforelse
        </section>
        <section class="admin-panel">
            <div class="panel-head"><div><h2>Add a category</h2><p>New categories are available right away.</p></div></div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="admin-form">
                @csrf
                <label>Name<input name="name" required></label>
                <label>Slug (optional)<input name="slug" placeholder="generated from name"></label>
                <label>Icon key<input name="icon" placeholder="leaf"></label>
                <label>Description<input name="description"></label>
                <label>Sort order<input type="number" min="0" name="sort_order" value="0"></label>
                <label class="check-row"><input type="checkbox" name="is_active" value="1" checked> Active</label>
                <button class="primary-button" type="submit">Add category</button>
            </form>
        </section>
    </div>
@endsection
