<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    License,
    Subscription,
    Website
};
use App\Http\Requests\VerifyLicenseRequest;

class LicenseController extends Controller
{
    public function verify(VerifyLicenseRequest $request) 
    {
        $license = License::Bylicense($request->license_key)->first();
        if(is_null($license)) {
            return response()->json([
                "error" => 1,
                "message" => "Incorrect license key! Please enter a genuine license key before continuing."
            ], 403);
        }

        $current_subscription = Subscription::where('user_id', $license->user->id)->status(['active', 'trialing'])->first();
        if(is_null($current_subscription) || $license->status !== 'active') {
            return response()->json([
                "error" => 1,
                "message" => "The license key is out of date. If you want to keep using the service, please think about increasing your subscription."
            ], 403);
        }

        $website = Website::has('subscriptions')->where('website_url', 'LIKE', '%'.$request->websiteurl.'%')->status('active')->first();
        if(is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Access is not authorized! Without a registered website on your current subscription, access is restricted."
            ], 403);
        }

        $lead_limit = $current_subscription->package->lead_limit;
        if($lead_limit != null && $current_subscription->leads >= $lead_limit) {
            return response()->json([
                "error" => 1,
                "message" => "Sorry, you have exceeded your leads limit."
            ], 403);
        }

        return response()->json([
            "error" => 0,
            "data" => [
                "is_authorized" => true
            ],
            "message" => "License verification was successful! The license provided is valid and authenticated."
        ], 200);
    }

    public function get_details(Request $request) {
        $headers = $request->header();
        $license_key = $headers['licensekey'][0];
        $websiteurl = $headers['websiteurl'][0];
        if(!isset($headers['licensekey'][0])) {
            return response()->json([
                "error" => 1,
                "message" => "Unauthorized Access! Access is restricted without a valid license key. Please provide a valid license key to proceed."
            ], 403);
        }

        if(!isset($headers['websiteurl'][0])) {
            return response()->json([
                "error" => 1,
                "message" => 'Unauthorized Access! Access is restricted without a registered website. Please first check that your website is registered in "'. config('app.name') .'"'
            ], 403);
        }
        
        $license = License::Bylicense($license_key)->first();
        if(is_null($license)) {
            return response()->json([
                "error" => 1,
                "message" => "Incorrect license key!"
            ], 403);
        }

        $website = Website::where('website_url', 'LIKE', '%'.$websiteurl.'%')->status('active')->first();
        if(is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Access is not authorized! Without a registered website on your current subscription, access is restricted."
            ], 403);
        }

        $current_subscription = Subscription::where('user_id', $license->user->id)->orderBY('id', 'desc')->first();
        if(is_null($current_subscription)) {
            return response()->json([
                "error" => 1,
                "message" => "Please upgrade your subscription."
            ], 403);
        }

        return response()->json([
            "error" => 0,
            "data" => [
                "type" => $current_subscription->name,
                "license_status" => $license->status,
                'purchase_date' => $current_subscription->start_at->format('F j, Y'),
                'expire_at' => ($current_subscription->end_at) ? $current_subscription->end_at->format('F j, Y') : null
            ],
            "message" => "License details has been retrieved successfuly!"
        ], 200); 
    }
}
