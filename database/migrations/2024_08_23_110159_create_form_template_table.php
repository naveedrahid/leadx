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
        Schema::create('form_template', function (Blueprint $table) {
            $table->id();
            $table->string('form_name');
            $table->string('form_key');
            $table->longText('template')->nullable();
            $table->longText('custom_css')->nullable();
            $table->longText('settings')->nullable();
            $table->longText('messages')->nullable();
            $table->string('description')->nullable();
            $table->string('template_image')->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_template');
    }
};
