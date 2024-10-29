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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('package_id')->unsigned();
            $table->bigInteger('coupon_id')->unsigned()->nullable();
            $table->timestamp('coupon_expire_at')->nullable();
            $table->string('name')->nullable();
            $table->string('payment_method')->nullable();
            $table->double('amount')->nullable();
            $table->timestamp('next_billing_date')->nullable();
            $table->timestamp('trial_start_at')->nullable();
            $table->timestamp('trial_end_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('resumes_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->longText('note')->nullable();
            $table->string('status');
            $table->string('pm_subscription_id')->nullable();
            $table->string('pm_customer_id')->nullable();
            $table->string('pm_plan_id')->nullable();
            $table->string('pm_id')->nullable();
            $table->integer('leads')->default(0);
            $table->longText('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('subscriptions_websites', function (Blueprint $table) {
            $table->bigInteger('subscription_id')->unsigned();
            $table->bigInteger('website_id')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscriptions_websites');
    }
};
