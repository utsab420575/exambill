<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\ApiCheckMiddleWare;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('get-previous-session',[ApiController::class,'get_previous_session'])
    ->middleware(ApiCheckMiddleWare::class);


