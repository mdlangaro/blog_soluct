<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::get('/teste', [TesteController::class, 'teste']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::controller(PostController::class)->group(function() {
        //POST
        Route::post('post/', 'store')->name('post.store');
        Route::post('post/manageacceptability/{post}/', 'manageacceptability')->name('manageacceptability');
        Route::patch('post/{post}', 'update')->name('post.update');
        Route::delete('post/{post}', 'destroy')->name('post.destroy');
    });
    //AUTHOR
    Route::controller(AuthorController::class)->group(function() {
        Route::get('author/{author}', 'show')->name('author.show');
        Route::post('author/usertoauthor/', 'usertoauthor')->name('usertoauthor');
    });
    //COMMENT
    Route::controller(CommentController::class)->group(function() {
        Route::post('comment/', 'store')->name('comment.store');
        Route::patch('comment/{comment}', 'update')->name('comment.update');
        Route::delete('comment/{comment}', 'destroy')->name('comment.destroy');
    });
    //CATEGORY
    Route::controller(CategoryController::class)->group(function() {
        Route::patch('category/{category}', 'update')->name('category.update');
        Route::post('category/', 'store')->name('category.store');
        Route::delete('category/{category}', 'destroy')->name('category.destroy');
    });
    //GENRE
    Route::controller(GenreController::class)->group(function() {
        Route::patch('genre/{genre}', 'update')->name('genre.update');
        Route::post('genre/', 'store')->name('genre.store');
        Route::delete('genre/{genre}', 'destroy')->name('genre.destroy');
    });
});    

Route::middleware(RedirectIfAuthenticated::class)->group(function() {
    //USER
    Route::post('register', [UserController::class, 'create'])->name('user.register');
    //AUTH
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

//POST
Route::controller(PostController::class)->group(function() {
    Route::get('post', 'index')->name('post.index');
    Route::get('post/{post}', 'show')->name('post.show');
});
//COMMENT
Route::controller(CommentController::class)->group(function() {
    Route::get('comment/{post_id}', 'index')->name('comment.index');
    Route::get('comment/{comment}', 'show')->name('comment.show');
});
//CATEGORY
Route::controller(CategoryController::class)->group(function() {
    Route::get('category', 'index')->name('category.index');
    Route::get('category/{category}', 'show')->name('category.show');
});
//GENRE
Route::controller(GenreController::class)->group(function() {
    Route::get('genre', 'index')->name('genre.index');
    Route::get('genre/{genre}', 'show')->name('genre.show');
});