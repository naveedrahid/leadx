<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
// use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Admin\{
    HomeController as AdminHomeController,
    CustomerController as AdminCustomerController,
    PackageController as AdminPackageController,
    FeedBackController as AdminFeedBackController,
    CouponController as AdminCouponController,
    PluginController as AdminPluginController,
    FormTemplateController as AdminFormTemplateController,
    FormCategoryController as AdminFormCategoryController,
    KeywordController as AdminKeywordController,
};
use App\Http\Controllers\Customer\{
    HomeController as CustomerHomeController,
    WebsiteController as CustomerWebsiteController,
    SubscriptionController as CustomerSubscriptionController,
    LeadsController as CustomerLeadsController,
    BlockedIPController as CustomerBlockedIPController,
    BlockKeywordController,
    SpamKeywordController as CustomerSpamKeywordController,
    CustomerController as CustomersLeadDetailController,
    KeywordController,
    LeadStatusPageController,
    OrderController,
    WebhookController as CustomerWebhookController,
    PluginController as CustomerPluginController
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    ForgotPasswordController,
    ResetPasswordController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('app')->as('app.')->group(function () {
    Route::prefix('admin')->as('admin.')->group(function () {

        Route::prefix('keywords')->name('keyword.')->controller(AdminKeywordController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{id}/edit', 'edit')->name('edit');
        });

        Route::controller(AdminCustomerController::class)->prefix('customer')->as('customer.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::get('/subscription/invoice_download/{invoice_id}/{user_id}', 'invoice_download')->name('subscription.invoice_download');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::get('{id}/subscription/license', 'license')->name('subscription.license');
            Route::get('{id}/subscription/billing/history', 'billing_history')->name('subscription.billing.history');
            Route::get('{id}/subscription/billing', 'billing')->name('subscription.billing');
            Route::get('{id}/subscription', 'subscription')->name('subscription');
            Route::get('{id}', 'show')->name('show');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminPackageController::class)->prefix('package')->as('package.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::get('{id}', 'show')->name('show');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminFormTemplateController::class)->prefix('template')->as('template.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminFormCategoryController::class)->prefix('category')->as('category.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminCouponController::class)->prefix('coupon')->as('coupon.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::get('{id}', 'show')->name('show');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminPluginController::class)->prefix('plugin')->as('plugin.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminFeedBackController::class)->prefix('feedback')->as('feedback.')->group(function () {
            Route::get('{id}', 'show')->name('show');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(AdminHomeController::class)->group(function () {
            Route::get('/account-setting', 'account_setting')->name('account_setting');
            Route::get('/change-password', 'change_password')->name('change_password');
            Route::get('/', 'dashboard')->name('dashboard');
        });
    });

    Route::prefix('customer')->as('customer.')->group(function () {
        Route::controller(OrderController::class)->prefix('orders')->as('order.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('{order}', 'show')->name('show');
        });
        
        Route::controller(CustomerPluginController::class)->prefix('your_downloads')->as('plugin.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/documentation', 'documentation')->name('documentation');
        });

        Route::controller(CustomerLeadsController::class)->prefix('leads')->as('leads.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::prefix('leads-statuses')->name('lead-statuses.')->controller(LeadStatusPageController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/{id}/edit', 'edit')->name('edit');
        });

        // Route::prefix('keywords')->name('keyword.')->controller(KeywordController::class)->group(function () {
        //     Route::get('/', 'index')->name('index');
        //     Route::get('/create', 'create')->name('create');
        //     Route::get('/{id}/edit', 'edit')->name('edit');
        // });

        Route::controller(CustomerBlockedIPController::class)->prefix('blocked-ip')->as('blocked-ip.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::controller(CustomerSpamKeywordController::class)->prefix('spam-leads')->as('spam-leads.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::controller(CustomersLeadDetailController::class)->prefix('lead-customers')->as('lead-customers.')->group(function () {
            Route::get('/', 'index')->name('index');
        });

        Route::controller(CustomerWebsiteController::class)->prefix('website')->as('website.')->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::get('/', 'index')->name('index');
        });

        Route::controller(CustomerSubscriptionController::class)->prefix('subscription')->as('subscription.')->group(function () {
            Route::get('/invoice_download/{invoice_id}/{user_id}', 'invoice_download')->name('invoice_download');
            Route::get('/license', 'license')->name('license');
            Route::get('/billing/history', 'billing_history')->name('billing.history');
            Route::get('/payment/{uuid}', 'payment')->name('payment');
            Route::get('/billing', 'billing')->name('billing');
            Route::get('/', 'subscription')->name('index');
        });

        Route::controller(CustomerHomeController::class)->group(function () {
            Route::get('/account-setting', 'account_setting')->name('account_setting');
            Route::get('/change-password', 'change_password')->name('change_password');
            Route::get('/', 'dashboard')->name('dashboard');
        });

        Route::prefix('block-keywords')->name('block-keyword.')->controller(BlockKeywordController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('{id}/edit', 'edit')->name('edit');
        });
    });

    Route::as('auth.')->group(function () {
        Route::get('/login', [LoginController::class, 'show'])->name('login');
        Route::get('/register', [RegisterController::class, 'show'])->name('register');
        Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.forgot');
        Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    });
});

Route::post('/stripe/webhook', [CustomerWebhookController::class, 'handleStripeWebhook']);
//Route::get('/webhook/stripe', [CustomerWebhookController::class, 'handleStripeWebhook']);

Route::get('/checkout', [HomeController::class, 'checkout'])->name('checkout');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms-conditions', [HomeController::class, 'terms_conditions'])->name('terms_conditions');
Route::get('/customer-reviews', [HomeController::class, 'customerReviews'])->name('customer.reviews');
Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact');
Route::get('/features', [HomeController::class, 'featured'])->name('featured');

Route::get('/', [HomeController::class, 'home'])->name('home');