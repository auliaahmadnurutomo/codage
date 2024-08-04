<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\setting\SettingStaffPosition\SettingStaffPositionController;

Route::get('/',[SettingStaffPositionController::class,'PageIndex']);
Route::get('create',[SettingStaffPositionController::class,'Create']);
Route::post('store',[SettingStaffPositionController::class,'Store']);
Route::get('detail/{id}',[SettingStaffPositionController::class,'Edit']);
Route::post('update',[SettingStaffPositionController::class,'Update']);
Route::get('activation',[SettingStaffPositionController::class,'Activation']);
Route::get('filter',[SettingStaffPositionController::class,'Filter']);
Route::get('delete',[SettingStaffPositionController::class,'Delete']);