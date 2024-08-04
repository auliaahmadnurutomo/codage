<?php
use Illuminate\Support\Facades\Route;

Route::get('logout', function () {
    Auth::logout();
    return redirect('login');
});

Route::middleware('auth')->group(function() {

// setting
Route::group(['prefix' => 'settingDepartment'], __DIR__ . '/setting/Route_SettingDepartment.php');
Route::group(['prefix' => 'settingStaffPosition'], __DIR__ . '/setting/Route_SettingStaffPosition.php');
Route::group(['prefix' => 'settingMenuAccess'], __DIR__ . '/root/Route_SettingMenuAccess.php');
Route::group(['prefix' => 'roleUsers'], __DIR__ . '/setting/Route_RoleUsers.php');
Route::group(['prefix' => 'settingUsers'], __DIR__ . '/setting/Route_SettingUsers.php');
Route::group(['prefix' => 'usersProfile'], __DIR__ . '/setting/Route_usersProfile.php');
Route::group(['prefix' => 'settingOffice'], __DIR__ . '/setting/Route_SettingOffice.php');
});
