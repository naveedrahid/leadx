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
        Schema::create('messages', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('chatbot_id');
            $t->unsignedBigInteger('website_id');
            $t->uuid('session_id')->nullable()->index();
            $t->unsignedBigInteger('user_id')->nullable();
            $t->enum('role', ['user', 'assistant'])->index();
            $t->longText('content');
            $t->unsignedBigInteger('qa_pair_id')->nullable();
            $t->decimal('score', 8, 4)->nullable();
            $t->json('sources')->nullable();
            $t->string('model')->nullable();
            $t->smallInteger('tokens_input')->nullable();
            $t->smallInteger('tokens_output')->nullable();
            $t->integer('latency_ms')->nullable();
            $t->enum('feedback', ['up', 'down'])->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
            $t->index(['chatbot_id', 'website_id']);
            $t->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
