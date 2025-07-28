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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('website_id')->unsigned();
            $table->bigInteger('wpform_id')->unsigned();
            $table->bigInteger('lead_status_id')->unsigned()->nullable();
            $table->string('wpform_name')->nullable();
            $table->string('uuid')->unique();
            $table->longText('form_data')->nullable();
            $table->boolean('is_viewed')->default(0);
            $table->enum('status', [
                'new', 
                'pending', 
                'assigned', 
                'in-progress', 
                'on-hold', 
                'follow-up', 
                'duplicate', 
                'contacted', 
                'qualified', 
                'unqualified', 
                'lost',
                'closed'
            ])->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
