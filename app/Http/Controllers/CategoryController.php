<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        
        return response()->json([
            'message' => $categories
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        if (Auth::user()->fladmin != 'Y') {
            return response()->json([
                'message' => "You don't have permission for it"
            ], 403);
        }
        
        $validated = $request->validated();
        $category = Category::create($validated);
        // dd($validated);
        return response()->json([
            'message' => $category
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return response()->json([
            'message' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (Auth::user()->fladmin != 'Y') {
            return response()->json([
                'message' => "You don't have permission for it"
            ], 403);
        }

        $return = $category->update($request->validated());
        if ($return) {
            return response()->json([
                'message' => 'Success',
                'data' => $category
            ], 200);
        }

        return response()->json([
            'message' => 'Internal error'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (Auth::user()->fladmin != 'Y') {
            return response()->json([
                'message' => "You don't have permission for it"
            ], 403);
        }
        
        $category->delete();

        return response()->json([
            'message' => 'Success'
        ], 200);
    }
}
