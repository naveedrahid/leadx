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
        Schema::create('lead_blocked_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->nullable();
            $table->boolean("is_blocked")->default(0);
            $table->integer('lead_id')->nullable();
            $table->integer('form_id')->nullable();
            $table->integer('website_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_user_ips');
    }
};
