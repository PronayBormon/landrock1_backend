<?php

use App\Http\Controllers\API\PageController;
use Illuminate\Support\Facades\Route;

Route::get('pages', [PageController::class, 'index']);
Route::get('pages/slug/{slug}', [PageController::class, 'showBySlug']);
Route::get('pages/faqs', [PageController::class, 'faqs']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('pages', [PageController::class, 'store']);
    Route::post('pages/{page}', [PageController::class, 'update']);
    Route::delete('pages/{page}', [PageController::class, 'destroy']);
});
