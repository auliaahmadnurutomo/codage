<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingMenuAccessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_menu_access')->delete();
        
        \DB::table('skeleton_setting_menu_access')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_parent' => 0,
                'menu_order' => 0,
                'name' => 'Root',
                'type' => 1,
                'url' => '#',
                'icon' => 'fa fa-key',
                'status' => 1,
                'sess_name' => '!s355R00T',
                'access' => 0,
            ),
            1 => 
            array (
                'id' => 2,
                'id_parent' => 1,
                'menu_order' => 1,
                'name' => 'Menu & Access',
                'type' => 1,
                'url' => 'settingMenuAccess',
                'icon' => 'fa fa-cogs',
                'status' => 1,
                'sess_name' => 'root',
                'access' => 0,
            ),
            2 => 
            array (
                'id' => 3,
                'id_parent' => 0,
                'menu_order' => 2,
                'name' => 'Department',
                'type' => 1,
                'url' => 'settingDepartment',
                'icon' => 'far fa-square',
                'status' => 1,
                'sess_name' => 'settingDepartment',
                'access' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'id_parent' => 0,
                'menu_order' => 3,
                'name' => 'Staff Position',
                'type' => 1,
                'url' => 'settingStaffPosition',
                'icon' => 'far fa-square',
                'status' => 1,
                'sess_name' => 'settingStaffPosition',
                'access' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'id_parent' => 0,
                'menu_order' => 4,
                'name' => 'User Login',
                'type' => 1,
                'url' => 'settingUsers',
                'icon' => 'far fa-square',
                'status' => 1,
                'sess_name' => 'settingUsers',
                'access' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'id_parent' => 0,
                'menu_order' => 5,
                'name' => 'Setting Office',
                'type' => 1,
                'url' => 'settingOffice',
                'icon' => 'far fa-square',
                'status' => 1,
                'sess_name' => 'settingOffice',
                'access' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'id_parent' => 0,
                'menu_order' => 6,
                'name' => 'User Roles',
                'type' => 1,
                'url' => 'roleUsers',
                'icon' => 'far fa-square',
                'status' => 1,
                'sess_name' => 'roleUsers',
                'access' => 1,
            ),
        ));
        
        
    }
}