<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Post::where('is_published', true);

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->input('q').'%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $posts = $query->orderByDesc('is_pinned')
            ->latest('published_at')
            ->orderByDesc('id')
            ->paginate(7)
            ->withQueryString();

        $categories = Post::query()->distinct()->orderBy('category')->pluck('category');

        return view('posts.index', compact('posts', 'categories'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        if (! $post->is_published) {
            abort(404);
        }

        $post->views += 1;
        $post->save();

        return view('posts.show', compact('post'));
    }
}
