<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\DestroyPostRequest;
use Illuminate\Support\Facades\Storage;

/**
 * @group Posts
 *
 * API endpoints for managing posts
 */
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')->orderByDesc('created_at')->get();
        return PostResource::collection($posts);
    }

    public function store(CreatePostRequest $request)
    {
        $user = $request->user();
        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('images', 'public');
        }

        // Create a new post
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $user->id,
            'image_path' => $image_path,
        ]);

        return new PostResource($post);
    }

    public function show(Post $post)
    {
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            abort(403);
        }

        if ($request->has('title')) {
            $post->title = $request->input('title');
        }
        if ($request->has('body')) {
            $post->body = $request->input('body');
        }

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $post->image_path = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return new PostResource($post);
    }

    public function destroy(DestroyPostRequest $request, Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
