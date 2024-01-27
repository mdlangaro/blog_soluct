<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManageAcceptabilityRequest;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\PostUser;
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
            $post->update(['view_count' => $post['view_count']+1]);
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
        if (trim($user->flauthor) != 'Y') {
            return response()->json([
                'message' => 'User is not an author'
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
        $post->update(['view_count' => $post['view_count']+1]);

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
                'message' => 'User is not the author'
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
                'message' => 'User is not the author'
            ], 403);
        }

        return response()->json([
            'message' => 'Success'
        ], 200);
    }

    public function manageAcceptability(ManageAcceptabilityRequest $request, Post $post)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $relationRegister = PostUser::where(['user_id' => $user->id, 'post_id' => $post->id])->first();
        if (!$relationRegister && $validated['action'] != -1) {
            $post->update(['acceptability' => $validated['action'] == 1 ? $post['acceptability'] + 1 : $post['acceptability'] - 1]);
            PostUser::create(['user_id' => $user->id, 'post_id' => $post->id, 'code_vote' => $validated['action']]);

            return response()->json([
                'message' => 'Your opinion was succesfully computed!'
            ]);
        }
        
        if ($relationRegister && $validated['action'] == -1) {
            $post->update(['view_count' => $post['view_count']+1]);
            $post->update(['acceptability' => $relationRegister['code_vote'] == 1 ? $post['acceptability'] - 1 : $post['acceptability'] + 1]);
            $relationRegister->delete();

            return response()->json([
                'message' => 'Your opinion was succesfully deleted!'
            ]);
        }
        
        if ($validated['action'] == -1) {
            return response()->json([
                'message' => "You are not able to remove your opinion because you have not opinionated yet."
            ], 400);
        }
        return response()->json([
            'message' => 'You have already opinionated.'
        ], 400);
    }
}