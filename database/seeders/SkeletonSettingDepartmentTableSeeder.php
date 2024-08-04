<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingDepartmentTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_department')->delete();
        
        \DB::table('skeleton_setting_department')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_office' => 1,
                'code' => 'RNDs',
                'name' => 'Research and Development',
                'status' => 1,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 16:19:35',
            ),
            1 => 
            array (
                'id' => 2,
                'id_office' => 1,
                'code' => 'ddd',
                'name' => 'fdfdf',
                'status' => 1,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 18:45:47',
            ),
        ));
        
        
    }
}