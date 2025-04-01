<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::apiResource('post' , PostController::class);
Route::get('user/{userId}/posts',[PostController::class , 'getUserPosts']);
