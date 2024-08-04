<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(SkeletonSettingCorporateTableSeeder::class);
        $this->call(SkeletonSettingDepartmentTableSeeder::class);
        $this->call(SkeletonSettingMenuAccessTableSeeder::class);
        $this->call(SkeletonSettingMenuTemplateTableSeeder::class);
        $this->call(SkeletonSettingOfficeTableSeeder::class);
        $this->call(SkeletonSettingPositionTableSeeder::class);
        $this->call(SkeletonSettingTemplateAccessTableSeeder::class);
        $this->call(SkeletonUsersInfoTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
