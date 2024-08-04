<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingCorporateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_corporate')->delete();
        
        \DB::table('skeleton_setting_corporate')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'SYS',
                'name' => 'System',
                'status' => 1,
                'id_user_insert' => 1,
                'dt_insert' => '2024-08-04 16:19:35',
            ),
        ));
        
        
    }
}