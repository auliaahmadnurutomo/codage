<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\SettingUsers\SettingUsersController;

Route::get('/',[SettingUsersController::class,'PageIndex']);
Route::get('create',[SettingUsersController::class,'Create']);
Route::post('store',[SettingUsersController::class,'Store']);
Route::get('detail/{id}',[SettingUsersController::class,'Edit']);
Route::post('update',[SettingUsersController::class,'Update']);
Route::get('activation',[SettingUsersController::class,'Activation']);
Route::get('filter',[SettingUsersController::class,'Filter']);
Route::get('delete',[SettingUsersController::class,'Delete']);