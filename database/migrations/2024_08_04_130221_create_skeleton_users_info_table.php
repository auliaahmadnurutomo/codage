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
        Schema::create('skeleton_users_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_office')->nullable();
            $table->unsignedBigInteger('id_department')->nullable();
            $table->unsignedBigInteger('id_position')->nullable();
            $table->bigInteger('id_access_template')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('origin_photo')->nullable();
            $table->text('thumb_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_users_info');
    }
};
