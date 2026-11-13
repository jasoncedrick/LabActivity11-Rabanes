@extends('layouts.app')
@section('content')
<a href="{{ route('posts.index') }}" class="mb-6 inline-block text-sm font-semibold text-emerald-800 hover:underline">← All community updates</a>
<article class="mx-auto max-w-3xl rounded-xl border border-stone-200 bg-white p-6 shadow-sm sm:p-10">
    <div class="mb-4 flex gap-2 text-xs font-semibold">
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-800">{{ $post->category }}</span>
        @if ($post->is_pinned)<span class="rounded-full bg-amber-100 px-3 py-1 text-amber-900">Pinned</span>@endif
    </div>
    <h1 class="text-3xl font-bold leading-tight sm:text-4xl">{{ $post->title }}</h1>
    <p class="mt-4 border-b border-stone-200 pb-6 text-sm text-slate-500">{{ $post->published_at?->format('F d, Y') }} · Author #{{ $post->author_id }} · {{ $post->views }} views</p>
    <div class="mt-6 whitespace-pre-line leading-8">{{ $post->body }}</div>
</article>
@endsection
