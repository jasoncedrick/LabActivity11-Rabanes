@extends('layouts.app')
@section('title', $author->name.' - Purok Rabanes')
@section('content')
<a href="{{ route('posts.index') }}" class="mb-6 inline-block text-sm font-semibold text-emerald-800 hover:underline">← All community updates</a>
<div class="mb-8 rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
    <h1 class="text-2xl font-bold">{{ $author->name }}</h1>
    <p class="mt-1 text-slate-600">{{ $author->position }}</p>
    <p class="mt-4 text-sm text-slate-500">{{ $posts->count() }} published posts · {{ $commentsReceived }} comments received</p>
</div>
<div class="grid gap-5 md:grid-cols-2">
    @forelse ($posts as $post)
        @include('posts._card')
    @empty
        <p class="rounded-xl border border-dashed border-stone-300 p-10 text-center md:col-span-2">No published posts yet.</p>
    @endforelse
</div>
@endsection
