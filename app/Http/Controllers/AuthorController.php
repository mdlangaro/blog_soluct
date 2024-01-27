<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAuthorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {        
        return response()->json([
            'message' => $author
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAuthorRequest  $request
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        //
    }

    public function usertoauthor(StoreAuthorRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();
        if ($user->flauthor == trim('Y')) {
            return response()->json([
                'message' => 'The user is already an author',
            ], 400);
        } else {
            $user->update(['flauthor', 'Y']);
            if (isset($validated['arr_genres'])) {
                $validated['arr_genres'] = '['.$validated['arr_genres'].']';
            }
            $validated['user_id'] = $user->id;
            // dd($validated);
            $author = Author::create($validated);
            
            return response()->json([
                'message' => 'success',
                'data' => $author,
            ], 200);
        }
    }
}
