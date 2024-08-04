<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\RoleUsers\RoleUsersController;

Route::get('/',[RoleUsersController::class,'PageIndex']);
Route::get('create',[RoleUsersController::class,'Create']);
Route::post('store',[RoleUsersController::class,'Store']);
Route::get('detail/{id}',[RoleUsersController::class,'Edit']);
Route::post('update',[RoleUsersController::class,'Update']);
Route::get('activation',[RoleUsersController::class,'Activation']);
Route::get('filter',[RoleUsersController::class,'Filter']);
Route::get('delete',[RoleUsersController::class,'Delete']);