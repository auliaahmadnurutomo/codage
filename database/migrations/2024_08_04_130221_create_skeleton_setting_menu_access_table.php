<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skeleton_setting_menu_access', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_parent');
            $table->unsignedMediumInteger('menu_order')->default(0);
            $table->string('name', 100);
            $table->tinyInteger('type')->default(1);
            $table->string('url', 200)->default('#');
            $table->string('icon', 50)->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('sess_name', 100)->nullable();
            $table->unsignedTinyInteger('access')->default(0)->comment('0 = all users, 1= root admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_setting_menu_access');
    }
};
