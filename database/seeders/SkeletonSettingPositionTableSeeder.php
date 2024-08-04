<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingPositionTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_position')->delete();
        
        \DB::table('skeleton_setting_position')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_office' => 1,
                'code' => 'rADM',
                'name' => 'Root Admin',
                'status' => 1,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 16:19:35',
            ),
            1 => 
            array (
                'id' => 2,
                'id_office' => 1,
                'code' => 'ffMng',
                'name' => 'manager',
                'status' => 1,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 18:46:20',
            ),
        ));
        
        
    }
}