<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@email.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$t70Z18NLmMLC/wDDBrxvC.pnXWGWIcCa53e1Dnuhztrq4TOZUE/hS',
                'remember_token' => NULL,
                'created_at' => NULL,
                'updated_at' => '2024-08-04 11:17:47',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'test',
                'email' => 'test@mail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$DfHKUICPEFcwm8qUbBMgpur8Tq1zgppAZ81Yths8x.PHXauOTCynW',
                'remember_token' => NULL,
                'created_at' => '2024-08-04 11:21:25',
                'updated_at' => '2024-08-04 13:03:57',
            ),
        ));
        
        
    }
}