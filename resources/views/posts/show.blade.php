@extends('layouts.app')
@section('title', $post->title.' - Purok Rabanes')
@section('content')
<a href="{{ route('posts.index') }}" class="mb-6 inline-block text-sm font-semibold text-emerald-800 hover:underline">← All community updates</a>
<article class="mx-auto max-w-3xl rounded-xl border border-stone-200 bg-white p-6 shadow-sm sm:p-10">
    <div class="mb-4 flex flex-wrap gap-2 text-xs font-semibold">
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-800">{{ $post->category }}</span>
        @if ($post->is_pinned)<span class="rounded-full bg-amber-100 px-3 py-1 text-amber-900">Pinned</span>@endif
        @foreach ($post->tags as $tag)
            <a href="{{ route('posts.index', ['tag' => $tag->name]) }}" class="rounded-full border border-sky-200 bg-sky-50 px-3 py-1 font-normal text-sky-800 hover:bg-sky-100">#{{ $tag->name }}</a>
        @endforeach
    </div>
    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ $post->title }}</h1>
    <p class="mt-4 border-b border-stone-200 pb-6 text-sm text-slate-500">
        {{ $post->published_at?->format('F d, Y') }} ·
        <a href="{{ route('authors.show', $post->author) }}" class="font-semibold text-emerald-800 hover:underline">{{ $post->author->name }}</a>
        · {{ $post->views }} views
    </p>
    <div class="mt-6 whitespace-pre-line leading-8">{{ $post->body }}</div>
</article>

<section class="mx-auto mt-8 max-w-3xl">
    <h2 class="mb-4 text-xl font-bold">Comments ({{ $post->comments->count() }})</h2>

    @auth
        <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6 rounded-xl border border-stone-200 bg-white p-5">
            @csrf
            @include('partials.errors')
            <label for="body" class="mb-1 block text-sm font-medium">Write a comment</label>
            <textarea id="body" name="body" rows="3" class="w-full rounded-lg border px-3 py-2 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
            <button class="mt-3 rounded-lg bg-emerald-800 px-5 py-2 font-semibold text-white hover:bg-emerald-900">Post comment</button>
        </form>
    @endauth
    @guest
        <p class="mb-6 rounded-xl border border-dashed border-stone-300 p-5 text-center text-sm">
            <a href="{{ route('login') }}" class="font-semibold text-emerald-800 underline">Sign in to comment</a>
        </p>
    @endguest

    <div class="space-y-3">
        @forelse ($post->comments as $comment)
            <div class="rounded-xl border border-stone-200 bg-white p-4">
                <p class="text-sm"><span class="font-semibold">{{ $comment->user->name }}</span> <span class="text-slate-500">· {{ $comment->created_at->diffForHumans() }}</span></p>
                <p class="mt-1 leading-7">{{ $comment->body }}</p>
            </div>
        @empty
            <p class="text-sm text-slate-500">No comments yet.</p>
        @endforelse
    </div>
</section>
@endsection
