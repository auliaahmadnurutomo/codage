<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingOfficeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_office')->delete();
        
        \DB::table('skeleton_setting_office')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_corporate' => 1,
                'code' => 'HLD',
                'name' => 'Holding',
                'status' => 1,
                'img_office' => '',
                'img_office_thumb' => '',
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 16:19:35',
                'timezone' => 'Asia/Jakarta',
            ),
            1 => 
            array (
                'id' => 2,
                'id_corporate' => 1,
                'code' => 'sdfsdf',
                'name' => 'dssdf',
                'status' => 1,
                'img_office' => NULL,
                'img_office_thumb' => NULL,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 19:04:12',
                'timezone' => NULL,
            ),
        ));
        
        
    }
}