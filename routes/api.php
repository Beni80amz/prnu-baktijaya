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
Route::apiResource('articles', \App\Http\Controllers\Api\ArtikelController::class)->only(['index', 'show']);
Route::apiResource('galleries', \App\Http\Controllers\Api\GaleriController::class)->only(['index'])->names('api.galeri');

// Utilities
Route::get('/jadwal-sholat', [\App\Http\Controllers\Api\UtilityController::class, 'getJadwalSholat']);
Route::get('/kota', [\App\Http\Controllers\Api\UtilityController::class, 'getCities']);
Route::get('/settings', [\App\Http\Controllers\Api\UtilityController::class, 'getSettings']);
Route::get('/agenda', [\App\Http\Controllers\Api\AgendaController::class, 'index']);
Route::get('/dawuh', [\App\Http\Controllers\Api\UtilityController::class, 'getDawuh']);
Route::get('/categories', [\App\Http\Controllers\Api\UtilityController::class, 'getCategories']);
Route::get('/organization', [\App\Http\Controllers\Api\UtilityController::class, 'getOrganization']);
Route::get('/kas', [\App\Http\Controllers\Api\KasController::class, 'index']);
Route::get('/kas/history', [\App\Http\Controllers\Api\KasController::class, 'history']);
Route::get('/kas/reports', [\App\Http\Controllers\Api\KasController::class, 'reports']);
Route::get('/kas/download-report', [\App\Http\Controllers\Api\KasController::class, 'downloadReport']);

// New Mobile Features
Route::apiResource('umkm', \App\Http\Controllers\Api\UmkmController::class)->only(['index', 'show'])->names('api.umkm');
Route::post('/tanya-kiai', [\App\Http\Controllers\Api\TanyaKiaiController::class, 'chat']);
Route::apiResource('ruang-doa', \App\Http\Controllers\Api\RuangDoaController::class)->only(['index', 'store']);
Route::get('/mosques', [\App\Http\Controllers\Api\MosqueController::class, 'index']);
Route::get('/profile', [\App\Http\Controllers\Api\ProfileController::class, 'index']);
Route::get('/zakat/config', [\App\Http\Controllers\Api\ZakatController::class, 'config']);

// Live Streaming
Route::get('/live-streaming', [\App\Http\Controllers\Api\LiveStreamingController::class, 'index']);
Route::get('/live-streaming/chats', [\App\Http\Controllers\Api\LiveStreamingController::class, 'getChats']);
Route::post('/live-streaming/chat', [\App\Http\Controllers\Api\LiveStreamingController::class, 'sendChat']);
Route::post('/live-streaming/attendance', [\App\Http\Controllers\Api\LiveStreamingController::class, 'submitAttendance']);

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
