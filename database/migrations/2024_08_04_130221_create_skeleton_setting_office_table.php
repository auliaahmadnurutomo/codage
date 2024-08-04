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
        Schema::create('skeleton_setting_office', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_corporate');
            $table->string('code', 100)->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('img_office')->nullable();
            $table->text('img_office_thumb')->nullable();
            $table->unsignedBigInteger('id_user_insert');
            $table->dateTime('dt_insert')->useCurrent();
            $table->string('timezone', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_setting_office');
    }
};
