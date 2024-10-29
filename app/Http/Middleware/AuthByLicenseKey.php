<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\{
    license,
    Subscription,
    Website
};

class AuthByLicenseKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headers = $request->header();
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

        $license = License::Bylicense($headers['licensekey'][0])->first();
        if(is_null($license)) {
            return response()->json([
                "error" => 1,
                "message" => "Unauthorized Access! Without a valid licensing key, access is limited. Please enter a genuine license key before continuing."
            ], 403);
        }

        $current_subscription = Subscription::where('user_id', $license->user->id)->status(['active', 'trialing'])->first();
        if(is_null($current_subscription) || $license->status !== 'active') {
            return response()->json([
                "error" => 1,
                "message" => "The license key is out of date. If you want to keep using the service, please think about increasing your subscription."
            ], 403);
        }

        $websiteurl = rtrim($headers['websiteurl'][0], '/');
        $website = Website::where('website_url', 'LIKE', '%'.$websiteurl.'%')->status('active')->first();
        if(is_null($website)) {
            return response()->json([
                "error" => 1,
                "message" => "Access is not authorized! Without a registered website on your current subscription, access is restricted."
            ], 403);
        }

        return $next($request);
    }
}
