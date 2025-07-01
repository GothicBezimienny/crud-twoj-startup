<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);
Route::apiResource('emails', EmailController::class);

Route::post('users/{user}/send-welcome', [UserController::class, 'sendWelcomeEmail']);

