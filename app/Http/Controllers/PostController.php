<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private array $categories = ['Announcement', 'Health', 'Peace & Order', 'Ayuda', 'Education'];

    public function index(Request $request)
    {
        $query = Post::with(['author', 'tags'])
            ->withCount('comments')
            ->where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->input('tag'));
            });
        }

        $posts = $query->orderByDesc('is_pinned')
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(7)
            ->withQueryString();

        $categories = $this->categories;
        $tags = Tag::orderBy('name')->get();

        return view('posts.index', compact('posts', 'categories', 'tags'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        if (! $post->is_published) {
            abort(404);
        }

        $post->views += 1;
        $post->save();

        $post->load([
            'author',
            'tags',
            'comments' => fn ($q) => $q->latest()->orderByDesc('id'),
            'comments.user',
        ]);

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        abort_if(auth()->user()->author === null, 403);

        $categories = $this->categories;
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->safe()->except('tags');
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        $post = $request->user()->author->posts()->create($data);
        $post->tags()->sync($request->validated('tags'));

        if (! $post->is_published) {
            return redirect()->route('posts.index')->with('status', 'Draft saved.');
        }

        return redirect()->route('posts.show', $post->id)->with('status', 'Post published.');
    }
}
