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
        Schema::create('skeleton_setting_template_access', function (Blueprint $table) {
            $table->unsignedInteger('id_menu_access')->index('fk_id_menu_access');
            $table->unsignedBigInteger('id_menu_template')->index('fk_id_menu_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_setting_template_access');
    }
};
