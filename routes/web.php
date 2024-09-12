<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('signup');
});
Route::get('/new', function () {
    return view('new'); // 'new' refers to the 'new.blade.php' view file.
})->name('new');
Route::get('/profile', function () {
    return view('profile'); // 'new' refers to the 'new.blade.php' view file.
})->name('profile');

//search
Route::get('/search', [SearchController::class, 'search'])->name('search');




//Route::get('/new', 'AdminController@showAllPosts')->name('new');


Route::get('/view-users', [AdminController::class, 'viewUsers'])->name('view-users');
Route::post('/logoutadmin',[AdminController::class,'logout']);
Route::get('/new', [AdminController::class, 'showAllPosts'])->name('new');
Route::delete('/users/{id}', [AdminController::class,'destroy'])->name('delete.user');
Route::patch('/posts/{id}/accept', [AdminController::class,'accept'])->name('accept.post');
Route::delete('/posts/{id}', [AdminController::class,'destroyPost'])->name('delete.post');


Route::post('/register',[UserController::class,'register']);
Route::post('/logout',[UserController::class,'logout']);
Route::post('/login',[UserController::class,'login']);
Route::get('/profile', [UserController::class,'showProfile'])->name('profile');
Route::get('/users/{id}', [UserController::class, 'showOtherProfile'])->name('otherProfile');

Route::get('/viewpeople', [UserController::class, 'viewPeople'])->name('viewpeople');



Route::post('/create', [PostController::class,'createPost']);
Route::middleware(['auth'])->group(function () {
    Route::get('/view-feed', [PostController::class, 'showApprovedPosts'])->name('view-feed');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/view-other', [PostController::class, 'showOtherPosts'])->name('view-other');
});

Route::post('/property/add/{post}', [PropertyController::class,'addToCart'])->name('cart.add');