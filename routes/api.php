<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    LeadController,
    CustomerLeadController,
    PackageController,
    CouponController,
    WebsiteController,
    CustomerController,
    SubscriptionController,
    LicenseController,
    GuestController,
    FeedBackController,
    PluginController,
    AdminDashboardController,
    CustomerDashboardController,
    FormCategoryController,
    FormTemplateController,
    BlockedIPController,
    SpamKeywordController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->middleware(['cors', 'json.response'])->as('api.')->group(function() {
    Route::middleware('auth:api')->group(function() {
        Route::controller(AuthController::class)->prefix('auth')->as('auth.')->group(function() {
            Route::post('account-setting', 'account_setting')->name('account.setting');
            Route::post('change-password', 'change_password')->name('account.change_password');
            Route::post('delete-account', 'delete_account')->name('account.delete');
            Route::post('logout', 'logout')->name('logout');
            Route::get('get-user', 'get_user')->name('get.user');
        });

        Route::controller(FeedBackController::class)->prefix('feedback')->as('feedback.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{feedback_id}', 'delete')->name('delete');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{feedback_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(PackageController::class)->prefix('package')->as('package.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{package_id}', 'delete')->name('delete');
            Route::post('/status/{package_id}', 'status')->name('update.status');
            Route::post('/update/{package_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{package_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(CouponController::class)->prefix('coupon')->as('coupon.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{coupon_id}', 'delete')->name('delete');
            Route::post('/status/{coupon_id}', 'status')->name('update.status');
            Route::post('/update/{coupon_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{coupon_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(WebsiteController::class)->prefix('website')->as('website.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{website_id}', 'delete')->name('delete');
            Route::post('/status/{website_id}', 'status')->name('update.status');
            Route::post('/update/{website_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{website_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(CustomerController::class)->prefix('customer')->as('customer.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{customer_id}', 'delete')->name('delete');
            Route::post('/status/{customer_id}', 'status')->name('update.status');
            Route::post('/update/{customer_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{customer_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(PluginController::class)->prefix('plugin')->as('plugin.')->group(function() {
            Route::post('/delete/{plugin_id}', 'delete')->name('delete');
            Route::post('/status/{plugin_id}', 'status')->name('update.status');
            Route::post('/update/{plugin_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{plugin_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(CustomerLeadController::class)->prefix('customer_leads')->as('customer_leads.')->group(function () {
            Route::post('/generate_pdf', 'generate_pdf')->name('generate.pdf');
            Route::post('/generate_excel', 'generate_excel')->name('generate.excel');
            Route::get('/forms', 'get_forms')->name('get.forms');
            Route::get('/website_form', 'website_forms')->name('website.forms');
            Route::get('/websites', 'get_websites')->name('get.websites');
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{lead_id}', 'delete')->name('delete');
            Route::post('/status/{lead_id}', 'status')->name('update.status');
            Route::post('/view/{lead_id}', 'view')->name('update.view');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{lead_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(SubscriptionController::class)->prefix('subscription')->as('subscription.')->group(function () {
            Route::get('/card', 'get_cards')->name('card');
            Route::post('/card/add', 'add_card')->name('card.add');
            Route::get('/card/default', 'default_card')->name('card.default');
            Route::post('/card/change_payment_method', 'change_payment_method')->name('card.change_payment_method');
            Route::post('/card/{id}/remove', 'remove_card')->name('card.remove');

            Route::post('/send_payment_link/{package_id}', 'send_payment_link')->name('send_payment_link');
            Route::post('/payment', 'payment')->name('payment');
            Route::post('/resume', 'resume_subscription')->name('resume');
            Route::post('/pause', 'pause_subscription')->name('pause');
            Route::post('/upgrade', 'upgrade_subscription')->name('upgrade');
            Route::post('/cancel', 'cancel_subscription')->name('cancel');
            Route::post('/website_update', 'website_update')->name('website_update');
            Route::post('/auto_renewal', 'change_auto_renewal')->name('change_auto_renewal');
            Route::post('/apply_discount', 'apply_discount')->name('apply.discount');
            Route::get('/payment_link/{uuid}', 'get_payment_link')->name('payment_link');
            Route::get('/invoices', 'get_invoices')->name('invoices');
            Route::get('/current', 'current_subscription')->name('current');
            Route::get('/single', 'get_subscription')->name('get.single');
            Route::get('/license', 'get_license')->name('get.license');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(AdminDashboardController::class)->prefix('admin_dashboard')->as('admin_dashboard.')->group(function() {
            Route::get('/get_data', 'get_data')->name('get_data');
        });

        Route::controller(CustomerDashboardController::class)->prefix('customer_dashboard')->as('customer_dashboard.')->group(function() {
            Route::get('/get_data', 'get_data')->name('get_data');
        });


        Route::controller(BlockedIPController::class)->prefix('blocked_ip')->as('blocked_ip.')->group(function() {
            Route::get('/', 'get_all')->name('get.all');
            Route::post('/blocked/{id}', 'blockedIP')->name('blocked.ip');
            Route::post('/unblocked/{id}', 'UnBlocked')->name('unblocked.ip');
        });

        Route::controller(SpamKeywordController::class)->prefix('spam-leads')->as('spam-leads.')->group(function() {
            Route::get('/', 'get_all')->name('get.all');
            Route::post('store', 'store')->name('store');
        });

    });

    Route::middleware(['throttle:100,1'])->group(function () {
        Route::controller(FormCategoryController::class)->prefix('category')->as('category.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{category_id}', 'delete')->name('delete');
            Route::post('/status/{category_id}', 'status')->name('update.status');
            Route::post('/update/{category_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{category_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });

        Route::controller(FormTemplateController::class)->prefix('template')->as('template.')->group(function() {
            Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
            Route::post('/delete/{template_id}', 'delete')->name('delete');
            Route::post('/status/{template_id}', 'status')->name('update.status');
            Route::post('/update/{template_id}', 'update')->name('update');
            Route::post('/create', 'store')->name('create');
            Route::get('/count', 'get_count')->name('get.count');
            Route::get('/{template_id}', 'get_by')->name('get.single');
            Route::get('/', 'get_all')->name('get.all');
        });
    });

    Route::controller(AuthController::class)->prefix('auth')->as('auth.')->group(function () {
        Route::post('/password/reset-password', 'password_reset')->name('password.reset');
        Route::post('/password/forgot-password', 'send_reset_link_response')->name('password.forgot');
        Route::post('/authenticate', 'authenticate')->name('authenticate');
        Route::post('/signup', 'signup')->name('signup');
    });


    Route::controller(SpamKeywordController::class)->middleware('auth.license')->prefix('keyword')->as('keyword.')->group(function () {
        Route::post('updateOrCreate', 'updateOrCreate');
        Route::get('selectBoxKeyword','selectBoxKeyword');
    });

    Route::controller(BlockedIPController::class)->prefix('track-ip')->as('track-ip.')->group(function() {
        Route::get('/{id}/{ip}', 'trackIP');
    });

    Route::controller(LeadController::class)->middleware('auth.license')->prefix('lead')->as('lead.')->group(function () {
        Route::post('/generate_pdf', 'generate_pdf')->name('generate.pdf');
        Route::post('/generate_excel', 'generate_excel')->name('generate.excel');
        Route::post('/bulk_delete', 'bulk_delete')->name('bulk.delete');
        Route::post('/delete/{lead_id}', 'delete')->name('delete');
        Route::post('/status/{lead_id}', 'status')->name('update.status');
        Route::post('/view/{lead_id}', 'view')->name('update.view');
        Route::post('/create', 'store')->name('create');
        Route::get('/count', 'get_count')->name('get.count');
        Route::get('/{lead_id}', 'get_by')->name('get.single');
        Route::get('/', 'get_all')->name('get.all');
    });




    Route::controller(GuestController::class)->prefix('guest')->as('guest.')->group(function () {
        Route::post('/validate_subscription', 'validate_subscription')->name('validate.subscription');
        Route::post('/create_subscription', 'create_subscription')->name('create.subscription');
        Route::post('/create_feedback', 'create_feedback')->name('create.feedback');
        Route::get('/get_packages', 'get_packages')->name('get.packages');
    });

    Route::controller(LicenseController::class)->prefix('license')->as('license.')->group(function () {
        Route::post('/verify', 'verify')->name('verify');
        Route::get('/details', 'get_details')->name('get.details');
    });
});




