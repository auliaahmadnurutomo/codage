<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonUsersInfoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_users_info')->delete();
        
        \DB::table('skeleton_users_info')->insert(array (
            0 => 
            array (
                'id' => 1,
                'id_user' => 1,
                'id_office' => 1,
                'id_department' => 1,
                'id_position' => 1,
                'id_access_template' => 1,
                'status' => 1,
                'origin_photo' => NULL,
                'thumb_photo' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'id_user' => 2,
                'id_office' => 2,
                'id_department' => 1,
                'id_position' => 1,
                'id_access_template' => 1,
                'status' => 1,
                'origin_photo' => NULL,
                'thumb_photo' => NULL,
            ),
        ));
        
        
    }
}