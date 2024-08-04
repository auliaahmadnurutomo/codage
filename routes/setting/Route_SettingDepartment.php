<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\SettingDepartment\SettingDepartmentController;

Route::get('/',[SettingDepartmentController::class,'PageIndex']);
Route::get('create',[SettingDepartmentController::class,'Create']);
Route::post('store',[SettingDepartmentController::class,'Store']);
Route::get('detail/{id}',[SettingDepartmentController::class,'Edit']);
Route::post('update',[SettingDepartmentController::class,'Update']);
Route::get('activation',[SettingDepartmentController::class,'Activation']);
Route::get('filter',[SettingDepartmentController::class,'Filter']);
Route::get('delete',[SettingDepartmentController::class,'Delete']);