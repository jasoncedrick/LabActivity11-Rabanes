@extends('layouts.app')
@section('title', 'New post - Purok Rabanes')
@section('content')
<a href="{{ route('posts.index') }}" class="mb-6 inline-block text-sm font-semibold text-emerald-800 hover:underline">← All community updates</a>
<div class="mx-auto max-w-3xl rounded-xl border border-stone-200 bg-white p-6 shadow-sm sm:p-10">
    <h1 class="mb-6 text-2xl font-bold">New post</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('posts.store') }}" class="space-y-5">
        @csrf

        <div>
            <label for="title" class="mb-1 block text-sm font-medium">Title</label>
            <input id="title" name="title" value="{{ old('title') }}" class="w-full rounded-lg border px-3 py-2 @error('title') border-red-500 @else border-stone-300 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category" class="mb-1 block text-sm font-medium">Category</label>
            <select id="category" name="category" class="w-full rounded-lg border px-3 py-2 @error('category') border-red-500 @else border-stone-300 @enderror">
                <option value="">Choose a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            @error('category')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="body" class="mb-1 block text-sm font-medium">Body</label>
            <textarea id="body" name="body" rows="7" class="w-full rounded-lg border px-3 py-2 @error('body') border-red-500 @else border-stone-300 @enderror">{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <fieldset>
            <legend class="mb-2 text-sm font-medium">Tags</legend>
            <div class="flex flex-wrap gap-4">
                @foreach ($tags as $tag)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, (array) old('tags', [])))>
                        {{ $tag->name }}
                    </label>
                @endforeach
            </div>
            @error('tags')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
            @error('tags.*')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </fieldset>

        <div>
            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))>
                Publish now
            </label>
            @error('is_published')
                <p class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>

        <button class="rounded-lg bg-emerald-800 px-5 py-2 font-semibold text-white hover:bg-emerald-900">Save post</button>
    </form>
</div>
@endsection
