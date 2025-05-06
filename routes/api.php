<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}', [ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy']);

    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});