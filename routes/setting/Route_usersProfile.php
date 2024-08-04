<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\usersProfile\usersProfileController;

Route::get('/',[usersProfileController::class,'Create']);
Route::get('create',[usersProfileController::class,'Create']);
Route::post('update',[usersProfileController::class,'Update']);