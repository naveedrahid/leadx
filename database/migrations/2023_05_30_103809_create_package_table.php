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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('duration')->nullable();
            $table->enum('duration_type', ['day', 'week', 'month', 'year'])->nullable();
            $table->boolean('duration_lifetime')->default(0);
            $table->double('regular_price')->nullable();
            $table->double('sale_price')->nullable();
            $table->double('strip_precent')->nullable();
            $table->string('trial_period_days')->nullable();
            $table->longText('features')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('recommended')->default(0);
            $table->string('sort')->default('0');
            $table->string('website_limit')->nullable();
            $table->string('lead_limit')->nullable();
            $table->boolean('app_access')->default(0);
            $table->boolean('is_private')->default(0);
            $table->boolean('is_checked')->default(0);
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
