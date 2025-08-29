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
        Schema::create('chatbots', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('user_id');
            $t->unsignedBigInteger('website_id');
            $t->string('name');
            $t->string('slug')->unique();
            $t->boolean('is_active')->default(true);
            $t->text('system_prompt')->nullable();
            $t->string('bubble_message')->nullable();
            $t->string('welcome_message')->nullable();
            $t->string('connect_message')->nullable();
            $t->string('offline_message')->nullable();
            $t->tinyInteger('interaction_type')->default(1);
            $t->string('language',10)->default('auto');
            $t->boolean('do_not_go_beyond')->default(false);
            $t->string('model')->nullable();
            $t->decimal('temperature',3,2)->default(0.20);
            $t->tinyInteger('top_k')->default(3);
            $t->decimal('confidence_threshold',5,4)->default(0.3000);
            $t->string('avatar_type',20)->nullable();
            $t->string('avatar_url')->nullable();
            $t->string('logo_url')->nullable();
            $t->string('color_accent',7)->nullable();
            $t->boolean('show_logo')->default(false);
            $t->boolean('show_datetime')->default(false);
            $t->boolean('transparent_trigger')->default(false);
            $t->smallInteger('trigger_avatar_size')->default(60);
            $t->string('position',10)->default('right');
            $t->string('footer_link')->nullable();
            $t->text('custom_css')->nullable();
            $t->json('settings')->nullable();
            $t->string('public_token')->nullable()->index();
            $t->smallInteger('iframe_width')->default(420);
            $t->smallInteger('iframe_height')->default(745);
            $t->json('domain_allowlist')->nullable();
            $t->boolean('moderation_on')->default(false);
            $t->timestamps();
            $t->index(['user_id','website_id']);
            $t->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
