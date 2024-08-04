<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SkeletonSettingTemplateAccessTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('skeleton_setting_template_access')->delete();
        
        \DB::table('skeleton_setting_template_access')->insert(array (
            0 => 
            array (
                'id_menu_access' => 5,
                'id_menu_template' => 2,
            ),
            1 => 
            array (
                'id_menu_access' => 1,
                'id_menu_template' => 1,
            ),
            2 => 
            array (
                'id_menu_access' => 2,
                'id_menu_template' => 1,
            ),
            3 => 
            array (
                'id_menu_access' => 3,
                'id_menu_template' => 1,
            ),
            4 => 
            array (
                'id_menu_access' => 4,
                'id_menu_template' => 1,
            ),
            5 => 
            array (
                'id_menu_access' => 5,
                'id_menu_template' => 1,
            ),
            6 => 
            array (
                'id_menu_access' => 6,
                'id_menu_template' => 1,
            ),
            7 => 
            array (
                'id_menu_access' => 7,
                'id_menu_template' => 1,
            ),
        ));
        
        
    }
}