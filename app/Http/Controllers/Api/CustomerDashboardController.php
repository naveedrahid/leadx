<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;

class CustomerDashboardController extends Controller
{
    public function resolveUser(Request $request) {
        if($request->filled('user_id')) {
            return User::whereId($request->user_id)->first();
        } else {
            return $request->user();
        }
    }

    public function get_data(Request $request) {
        $user = $this->resolveUser($request);
        if (is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $leads = Lead::byUser($user->id)->filterLeads($request)->get();
        $leadsCount = $leads->count();
        $viewed_count = $leads->where('is_viewed', 1)->count();
        $unviewed_count = $leads->where('is_viewed', 0)->count();
        $is_spam = $leads->where('is_spam', 1)->count();

        return response()->json([
            "error" => 0,
            "data" => [
                "leads" => [
                    "total_leads" => $leadsCount,
                    "total_viewed" => $viewed_count,
                    "total_unviewed" => $unviewed_count,
                    "is_spam" => $is_spam
                ]
            ],
            "message" => "Success",
        ], 200);
    }
}
