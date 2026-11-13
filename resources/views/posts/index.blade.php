@extends('layouts.app')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Community updates</h1>
    <span class="text-sm text-slate-500">{{ $posts->total() }} matching posts</span>
</div>
<form method="GET" action="{{ route('posts.index') }}" class="mb-8 flex flex-col gap-3 rounded-xl border border-stone-200 bg-white p-4 sm:flex-row sm:items-end">
    <div class="flex-1">
        <label for="q" class="mb-1 block text-sm font-medium">Search title</label>
        <input id="q" name="q" value="{{ request('q') }}" placeholder="Find a community update" class="w-full rounded-lg border border-stone-300 px-3 py-2 focus:outline-emerald-700">
    </div>
    <div>
        <label for="category" class="mb-1 block text-sm font-medium">Category</label>
        <select id="category" name="category" class="w-full rounded-lg border border-stone-300 px-3 py-2">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>
    </div>
    <button class="rounded-lg bg-emerald-800 px-5 py-2 font-semibold text-white hover:bg-emerald-900">Filter</button>
    <a href="{{ route('posts.index') }}" class="px-3 py-2 text-center text-sm underline">Reset</a>
</form>
@if ($errors->any())
    <p class="mb-5 text-red-700">{{ $errors->first() }}</p>
@endif
<div class="grid gap-5 md:grid-cols-2">
    @forelse ($posts as $post)
        <article class="flex flex-col rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-800">{{ $post->category }}</span>
                @if ($post->is_pinned)<span class="rounded-full bg-amber-100 px-3 py-1 text-amber-900">Pinned</span>@endif
            </div>
            <h2 class="text-xl font-bold"><a href="{{ route('posts.show', $post->id) }}" class="hover:text-emerald-800 hover:underline">{{ $post->title }}</a></h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($post->body, 150) }}</p>
            <div class="mt-auto flex flex-wrap justify-between gap-2 pt-5 text-xs text-slate-500">
                <span>{{ $post->published_at?->format('M d, Y') }} · Author #{{ $post->author_id }}</span>
                <span>{{ $post->views }} views</span>
            </div>
        </article>
    @empty
        <p class="rounded-xl border border-dashed border-stone-300 p-10 text-center md:col-span-2">No posts match your search.</p>
    @endforelse
</div>
<div class="mt-8">{{ $posts->links() }}</div>
<p class="mt-4 text-sm text-slate-500">Showing {{ $posts->firstItem() ?? 0 }}–{{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} posts</p>
@endsection
