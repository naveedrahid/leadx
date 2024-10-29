<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\SubscriptionInvoices;
use LaravelDaily\Invoices\Invoice as LdInvoice;
use LaravelDaily\Invoices\Classes\InvoiceItem as LdInvoiceItem;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Admin/Customer/Index');
    }

    public function create() : Response
    {
        return Inertia::render('Admin/Customer/Create');
    }

    public function edit($id) : Response
    {
        return Inertia::render('Admin/Customer/Edit', [
            'id' => $id
        ]);
    }

    public function show($id) : Response
    {
        return Inertia::render('Admin/Customer/Show', [
            'id' => $id
        ]);
    }

    public function billing($id) : Response
    {
        return Inertia::render('Admin/Customer/Payment/Billing', [
            'id' => $id
        ]);
    }

    public function subscription($id) : Response
    {
        return Inertia::render('Admin/Customer/Payment/Subscription', [
            'id' => $id
        ]);
    }

    public function billing_history($id) : Response
    {
        return Inertia::render('Admin/Customer/Payment/BillingHistory', [
            'id' => $id
        ]);
    }

    public function license($id) : Response
    {
        return Inertia::render('Admin/Customer/Payment/License', [
            'id' => $id
        ]);
    }

    public function invoice_download($invoice_id, $user_id) 
    {
        $subscription_invoice = SubscriptionInvoices::where('user_id', $user_id)->whereId($invoice_id)->first();
        if(is_null($subscription_invoice)) {
            return response()->json([
                'error' => 1,
                'message' => 'No Invoice Found!'
            ], 404);
        }

        $subscription_invoice->load('user', 'package', 'subscription', 'coupon');

        $seller = LdInvoice::makeParty([
            'name' => config('app.name'),
            'custom_fields' => [
                'Email' => config('mail.from.address')
            ],
        ]);

        $customer = LdInvoice::makeParty([
            'name' => $subscription_invoice->user->fullname,
            'custom_fields' => [
                'Email' => $subscription_invoice->user->email,
                'Phone Number' => $subscription_invoice->user->phone_number
            ],
        ]);

        $item = (new LdInvoiceItem)->title($subscription_invoice->description)->pricePerUnit($subscription_invoice->amount);
        if($subscription_invoice->coupon()->exists()) {
            $coupon = $subscription_invoice->coupon;
            $description = "{$coupon->discount} Discount ({$coupon->discount} off)";

            if($coupon->type == 'fixed') {
                $item = (new LdInvoiceItem)->title($subscription_invoice->description)->description($description)->pricePerUnit($subscription_invoice->amount)->discount($coupon->amount);
            } else {
                $item = (new LdInvoiceItem)->title($subscription_invoice->description)->description($description)->pricePerUnit($subscription_invoice->amount)->discountByPercent($coupon->amount);
            }
        }

        $date = Carbon::parse($subscription_invoice->date);

        $invoice = LdInvoice::make()
            ->sequence($subscription_invoice->id)
            ->serialNumberFormat('{SEQUENCE}')
            ->status(__('invoices::invoice.'.$subscription_invoice->status))
            ->currencySymbol(currency_symbol())
            ->currencyCode(currency_code())
            ->currencyFormat('{SYMBOL}{VALUE} {CODE}')
            ->seller($seller)
            ->buyer($customer)
            ->date($date)
            ->dateFormat('d/m/Y')
            ->addItem($item)
            ->logo(public_path('_app_assets/images/logos/logo.png'))
            ->filename('invoice-0000'. $subscription_invoice->id);

        return $invoice->stream();
    }
}
