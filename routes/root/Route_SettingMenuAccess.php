<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\root\SettingMenuAccess\SettingMenuAccessController;

Route::get('/',[SettingMenuAccessController::class,'PageIndex']);
Route::get('create',[SettingMenuAccessController::class,'Create']);
Route::post('store',[SettingMenuAccessController::class,'Store']);
Route::get('detail/{id}',[SettingMenuAccessController::class,'Edit']);
Route::post('update',[SettingMenuAccessController::class,'Update']);
Route::get('activation',[SettingMenuAccessController::class,'Activation']);
Route::get('filter',[SettingMenuAccessController::class,'Filter']);
Route::get('delete',[SettingMenuAccessController::class,'Delete']);