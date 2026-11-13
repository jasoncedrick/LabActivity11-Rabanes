<article class="flex flex-col rounded-xl border border-stone-200 bg-white p-6 shadow-sm">
    <div class="mb-4 flex flex-wrap gap-2 text-xs font-semibold">
        <span class="rounded-full bg-emerald-50 px-3 py-1 text-emerald-800">{{ $post->category }}</span>
        @if ($post->is_pinned)<span class="rounded-full bg-amber-100 px-3 py-1 text-amber-900">Pinned</span>@endif
    </div>
    <h2 class="text-xl font-bold"><a href="{{ route('posts.show', $post->id) }}" class="hover:text-emerald-800 hover:underline">{{ $post->title }}</a></h2>
    <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($post->body, 150) }}</p>
    <div class="mt-4 flex flex-wrap gap-2 text-xs">
        @foreach ($post->tags as $tag)
            <a href="{{ route('posts.index', ['tag' => $tag->name]) }}" class="rounded-full border border-sky-200 bg-sky-50 px-2.5 py-0.5 text-sky-800 hover:bg-sky-100">#{{ $tag->name }}</a>
        @endforeach
    </div>
    <div class="mt-auto flex flex-wrap justify-between gap-2 pt-5 text-xs text-slate-500">
        <span>{{ $post->published_at?->format('M d, Y') }} · <a href="{{ route('authors.show', $post->author) }}" class="font-semibold text-emerald-800 hover:underline">{{ $post->author->name }}</a></span>
        <span>{{ $post->views }} views · {{ $post->comments_count }} comments</span>
    </div>
</article>
