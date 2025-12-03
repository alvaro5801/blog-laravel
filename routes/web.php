<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController; 

/*
|--------------------------------------------------------------------------
| Rotas Públicas de Leitura
|--------------------------------------------------------------------------
*/
Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/post/{id}', [PostController::class, 'show'])->name('posts.show');
Route::get('/user/{id}', [UserController::class, 'show'])->name('users.show');
Route::get('/user/{id}/posts', [PostController::class, 'postsByUser'])->name('users.posts');


/*
|--------------------------------------------------------------------------
| Rotas de Interação (Likes/Dislikes)
|--------------------------------------------------------------------------
*/
Route::post('/post/{id}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/post/{id}/dislike', [PostController::class, 'dislike'])->name('posts.dislike');


/*
|--------------------------------------------------------------------------
| Rotas de Comentários (CRUD e Reações)
|--------------------------------------------------------------------------
*/
// 1. CRUD de Comentários (Usa Route::resource ou agrupamento)
Route::controller(CommentController::class)->group(function () {
    // Rota STORE é especial (aninhada em '/post/{id}')
    Route::post('/post/{postId}/comment', 'store')->name('comments.store');
    
    // Rotas de Edição/Atualização/Deleção de Comentário
    Route::get('/comment/{id}/edit', 'edit')->name('comments.edit');
    Route::put('/comment/{id}', 'update')->name('comments.update');
    Route::delete('/comment/{id}', 'destroy')->name('comments.destroy');

    // 2. Interações no Comentário
    Route::post('/comment/{id}/like', 'like')->name('comments.like');
    Route::post('/comment/{id}/dislike', 'dislike')->name('comments.dislike');
});