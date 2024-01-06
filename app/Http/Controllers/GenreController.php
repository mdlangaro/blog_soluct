<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genres = Genre::all();

        return response()->json([
            'data' => $genres
        ], 200);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByUser($id)
    {   
        User::findOrFail($id);
        $genres = Genre::all()->where('user_id', $id);

        return response()->json([
            'data' => $genres
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->id;
        $genre = Genre::create($validated);

        return response()->json([
            'message' => 'Success',
            'data' => $genre
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function show(Genre $genre)
    {
        return response()->json([
            'data' => $genre
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGenreRequest  $request
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $validated = $request->validated();
        $genre = $genre->update($validated);

        return response()->json([
            'message' => 'Success',
            'data' => $genre
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Genre  $genre
     * @return \Illuminate\Http\Response
     */
    public function destroy(Genre $genre)
    {
        $return = $genre->delete();
        if ($return) {
            return response()->json([
                'message' => 'Success',
            ], 200);
        }

        return response()->json([
            'message' => 'Internal error',
        ], 200);
    }
}
