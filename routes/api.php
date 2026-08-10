<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SyncController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — used by the offline-first PWA
|--------------------------------------------------------------------------
|
| Authenticated with Sanctum bearer tokens. The browser/PWA NEVER talks
| directly to the database; it talks only to these endpoints.
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum')->name('api.me');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sync/bootstrap', [SyncController::class, 'bootstrap'])->name('api.sync.bootstrap');
    Route::get('/sync/pull', [SyncController::class, 'pull'])->name('api.sync.pull');
    Route::post('/sync/push', [SyncController::class, 'push'])->name('api.sync.push');
    Route::post('/sync/ack', [SyncController::class, 'ack'])->name('api.sync.ack');
});
