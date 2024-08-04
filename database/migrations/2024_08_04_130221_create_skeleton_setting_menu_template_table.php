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
        Schema::create('skeleton_setting_menu_template', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_office');
            $table->string('name', 200);
            $table->unsignedTinyInteger('status')->default(1);
            $table->dateTime('dt_insert')->useCurrent();
            $table->unsignedBigInteger('id_user_insert');
            $table->dateTime('dt_update')->useCurrent();
            $table->unsignedBigInteger('id_user_update');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_setting_menu_template');
    }
};
