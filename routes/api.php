<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadImageController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/upload', [UploadImageController::class, 'store']);
Route::get('/image/{hash}', [UploadImageController::class, 'show']);
Route::delete('/image/{hash}', [UploadImageController::class, 'destroy']);
