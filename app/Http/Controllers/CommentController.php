<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $comments = Comment::all()->where('post_id', $post_id);
        if ($comments->isEmpty()) {
            return response()->json([
                'message' => 'None comment linked to this post.'
            ], 200);
        };

        return response()->json([
            'message' => $comments
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        $validated['user_id'] = $user->id;
        $comment = Comment::create($validated);
        
        return response()->json([
            'message' => $comment
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return response()->json([
            'message' => $comment
        ], 200);
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $user = Auth::user();
        if ($comment->user_id != $user->id) {
            return response()->json([
                'data' => 'User is not the author'
            ], 403);
        }
        $return = $comment->update($request->validated());
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
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $user = Auth::user();
        if ($comment->user_id != $user->id) {
            return response()->json([
                'message' => 'User is not the author.'
            ], 403);
        }
        $comment->delete();

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
