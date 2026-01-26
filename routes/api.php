<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});

// Public endpoints
Route::apiResource('news', \App\Http\Controllers\Api\BeritaController::class)->only(['index', 'show']);
Route::get('/news/{newsId}/comments', [\App\Http\Controllers\Api\NewsCommentController::class, 'index']);
Route::post('/news/{newsId}/comments', [\App\Http\Controllers\Api\NewsCommentController::class, 'store']);
Route::apiResource('artikel', \App\Http\Controllers\Api\ArtikelController::class)->only(['index', 'show']);
Route::apiResource('galeri', \App\Http\Controllers\Api\GaleriController::class)->only(['index']);

// Utilities
Route::get('/jadwal-sholat', [\App\Http\Controllers\Api\UtilityController::class, 'getJadwalSholat']);
Route::get('/kota', [\App\Http\Controllers\Api\UtilityController::class, 'getCities']);
Route::get('/settings', [\App\Http\Controllers\Api\UtilityController::class, 'getSettings']);
Route::get('/agenda', [\App\Http\Controllers\Api\UtilityController::class, 'getAgenda']);
Route::get('/dawuh', [\App\Http\Controllers\Api\UtilityController::class, 'getDawuh']);
Route::get('/categories', [\App\Http\Controllers\Api\UtilityController::class, 'getCategories']);
Route::get('/organization', [\App\Http\Controllers\Api\UtilityController::class, 'getOrganization']);
Route::get('/kas', [\App\Http\Controllers\Api\KasController::class, 'index']);
Route::get('/kas/history', [\App\Http\Controllers\Api\KasController::class, 'history']);
Route::get('/kas/reports', [\App\Http\Controllers\Api\KasController::class, 'reports']);
Route::get('/kas/download-report', [\App\Http\Controllers\Api\KasController::class, 'downloadReport']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
