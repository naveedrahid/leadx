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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code');
            $table->longText('description')->nullable();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->double('amount');
            $table->string('max_uses')->nullable();
            $table->string('max_uses_user')->nullable();
            $table->enum('duration', ['once', 'repeating'])->default('once');
            $table->string('duration_month')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->string('pm_coupon_id')->nullable();            
            $table->timestamps();
        });

        Schema::create('coupon_user', function (Blueprint $table) {
            $table->bigInteger('coupon_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('coupon_user');
    }
};
