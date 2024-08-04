<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingMenuTemplateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_menu_template')->delete();
        
        \DB::table('skeleton_setting_menu_template')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_office' => 1,
                'name' => 'Default',
                'status' => 1,
                'dt_insert' => '2024-08-04 16:19:35',
                'id_user_insert' => 1,
                'dt_update' => '2024-08-04 16:19:35',
                'id_user_update' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'id_office' => 1,
                'name' => 'Another',
                'status' => 1,
                'dt_insert' => '2024-08-04 18:51:11',
                'id_user_insert' => 1,
                'dt_update' => '2024-08-04 18:51:11',
                'id_user_update' => 1,
            ),
        ));
        
        
    }
}