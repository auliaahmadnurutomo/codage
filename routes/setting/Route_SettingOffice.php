<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\SettingOffice\SettingOfficeController;

Route::get('/',[SettingOfficeController::class,'PageIndex']);
Route::get('create',[SettingOfficeController::class,'Create']);
Route::post('store',[SettingOfficeController::class,'Store']);
Route::get('detail/{id}',[SettingOfficeController::class,'Edit']);
Route::post('update',[SettingOfficeController::class,'Update']);
Route::get('activation',[SettingOfficeController::class,'Activation']);
Route::get('filter',[SettingOfficeController::class,'Filter']);
Route::get('delete',[SettingOfficeController::class,'Delete']);