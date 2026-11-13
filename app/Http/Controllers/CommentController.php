<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->comments()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('posts.show', $post->id)->with('status', 'Comment posted.');
    }
}
