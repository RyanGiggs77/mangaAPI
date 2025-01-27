<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

Route::middleware(['auth:sanctum', EnsureFrontendRequestsAreStateful::class])->group(function () {
    Route::get('/mangas', [MangaController::class, 'index']);
    Route::get('/mangas/{slug}', [MangaController::class, 'show']);
    Route::post('/mangas', [MangaController::class, 'store']);
    Route::put('/mangas/{slug}', [MangaController::class, 'update']);
    Route::delete('/mangas/{slug}', [MangaController::class, 'delete']);

    // Chapter Routes
    Route::get('/mangas/{manga_slug}/chapters', [ChapterController::class, 'index']);
    Route::get('/mangas/{manga_slug}/chapters/{chapter_number}', [ChapterController::class, 'show']);
    Route::post('/mangas/{manga_slug}/chapters', [ChapterController::class, 'store']);
    Route::put('/mangas/{manga_slug}/chapters/{chapter_number}', [ChapterController::class, 'update']);
    Route::delete('/mangas/{manga_slug}/chapters/{chapter_number}', [ChapterController::class, 'delete']);


    // Page Routes
    Route::get('/mangas/{manga_slug}/chapters/{chapter_number}/pages', [PageController::class, 'index']);
    Route::get('/mangas/{manga_slug}/chapters/{chapter_number}/pages/{page_number}', [PageController::class, 'show']);
    Route::post('/mangas/{manga_slug}/chapters/{chapter_number}/pages', [PageController::class, 'store']);
    Route::put('/mangas/{manga_slug}/chapters/{chapter_number}/pages/{page_number}', [PageController::class, 'update']);
    Route::delete('/mangas/{manga_slug}/chapters/{chapter_number}/pages/{page_number}', [PageController::class, 'delete']);


    Route::get('/search/chapter', [SearchController::class, 'searchChapter']);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:api')->get('user', [AuthController::class, 'user']);