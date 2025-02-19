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
        Schema::create('spam_leads', function (Blueprint $table) {
            $table->id();
            $table->integer('lead_id');
            $table->integer('website_id');
            $table->integer('form_id');
            $table->json('keywords')->nullable();
            $table->json('found_keywords')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spam_leads');
    }
};
