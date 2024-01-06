<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        $posts->each(function ($post) {
            $post->update(['viewcount' => $post['viewcount']+1]);
        });

        return response()->json([
            'data' => $posts
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $user = Auth::user();
        if ($user->flauthor != 'Y') {
            return response()->json([
                'data' => 'User is not an author'
            ], 403);
        }
        $validated = $request->validated();
        $validated['user_id'] = $user->id;
        $post = Post::create($validated);
        
        return response()->json([
            'data' => $post
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $post->update(['viewcount' => $post['viewcount']+1]);

        return response()->json([
            'data' => $post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $user = Auth::user();
        if ($post->user_id != $user->id) {
            return response()->json([
                'data' => 'User is not the author'
            ], 403);
        }
        $return = $post->update($request->validated());
        if ($return) {
            return response()->json([
                'message' => 'Success'
            ], 200);
        }

        return response()->json([
            'message' => 'Internal error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = Auth::user();
        $post->delete();

        if ($post->user_id != $user->id) {
            return response()->json([
                'data' => 'User is not the author'
            ], 403);
        }

        return response()->json([
            'data' => 'Success'
        ], 200);
    }
}