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
        Schema::create('skeleton_default_configuration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference')->nullable();
            $table->string('configuration', 191);
            $table->string('identity_key', 191);
            $table->string('identity_operator', 191)->nullable();
            $table->string('identity_value', 191)->nullable();
            $table->unsignedTinyInteger('identity_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skeleton_default_configuration');
    }
};
