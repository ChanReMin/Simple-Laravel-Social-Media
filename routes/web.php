<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/admin-only', function () {
    return "Only admin";
})->middleware('can:visitAdminPage');

//User routes
Route::get('/', [UserController::class, 'showCorrectHomepage'])->name('login');
Route::post('/register', [UserController::class,'register'])->middleware('guest');
Route::post('/login', [UserController::class,'login'])->middleware('guest');
Route::post('/logout', [UserController::class,'logout'])->middleware('LoginRequired');
Route::get('/all-users', [UserController::class,'getAllUsers'])->middleware('LoginRequired');

//Follow routes
Route::post('/create-follow/{user:username}',[FollowController::class,'createFollow'])->middleware('LoginRequired');
Route::post('/remove-follow/{user:username}',[FollowController::class,'removeFollow'])->middleware('LoginRequired');

//Blog routes
Route::middleware('LoginRequired')->group(function()
{
    Route::get('/post/create', [PostController::class,'showCreateForm'])->name('post.show-create');
    Route::post('/post/create', [PostController::class,'store'])->name('post.store');
    Route::get('/post/{post}', [PostController::class,'viewSinglePost'])->name('post.single');
    Route::get('/post/{post}/edit', [PostController::class,'showEditForm'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class,'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class,'delete'])->name('post.delete');;

    //Search routes
    Route::get('/search/{keyword}',[PostController::class,'search']);
});



Route::middleware('LoginRequired')->group(function(){
    //Avatar routes
    Route::get('/profile/avatar/update',[UserController::class,'showAvatarForm'])->name('avatar.show');
    Route::post('/profile/avatar/update/{user:username}',[UserController::class,'storeAvatar'])->name('avatar.store');
    //Profile routes
    Route::get('/profile/{user:username}',[UserController::class,'viewProfile'])->name('user.profile');
    Route::get('/profile/{user:username}/followers',[UserController::class,'profileFollowers'])->name('user.followers');
    Route::get('/profile/{user:username}/following',[UserController::class,'profileFollowing'])->name('user.following');
    Route::get('/profile/{user:username}/edit',[UserController::class,'showEditProfileForm'])->name('user.edit');
    Route::put('/profile/{user:username}/edit',[UserController::class,'update'])->name('user.edit');
});

//Comment routes
Route::post('/post/{post}/comments', [CommentController::class, 'storeComment'])->middleware('LoginRequired');
Route::delete('/post/{post}/comments/{comment}', [CommentController::class, 'deleteComment'])->middleware('can:delete,comment');
Route::put('/post/{post}/comments/{comment}', [CommentController::class, 'updateComment'])->middleware('can:update,comment');


Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});




