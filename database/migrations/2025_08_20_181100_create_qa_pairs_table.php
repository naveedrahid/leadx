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
        Schema::create('qa_pairs', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('chatbot_id');
            $t->unsignedBigInteger('website_id');
            $t->unsignedBigInteger('user_id')->nullable();
            $t->text('question');
            $t->longText('answer');
            $t->boolean('is_active')->default(true);
            $t->unsignedTinyInteger('priority')->default(0);
            $t->json('tags')->nullable();
            $t->string('source_type', 16)->nullable();
            $t->string('source_url')->nullable();
            $t->timestamp('last_used_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
            $t->fullText(['question', 'answer'], 'ft_qa_question_answer');
            $t->index(['chatbot_id', 'website_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_pairs');
    }
};
