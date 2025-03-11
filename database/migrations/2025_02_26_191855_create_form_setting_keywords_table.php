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
        Schema::create('form_setting_keywords', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('form_id');
            $table->json('keyword');
            $table->string('form_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_setting_keywords');
    }
};
