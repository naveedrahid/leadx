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
        Schema::create('customer_forms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('form_id')->nullable();
            $table->string('form_name');
            $table->string('form_key')->unique();
            $table->unsignedBigInteger('website_id')->nullable();
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
        Schema::dropIfExists('customer_forms');
    }
};
