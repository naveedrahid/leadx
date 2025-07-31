<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlockKeywordRequest;
use App\Http\Resources\BlockKeywordResource;
use App\Models\BlockKeyword;
use App\Models\CustomerForm;
use App\Models\FormKeyword;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class BlockKeywordController extends Controller
{
    public function index(Request $request)
    {
    $userId = $request->user()->id;

    $blocks = BlockKeyword::with(['website', 'form'])
        ->where('user_id', $userId)
        ->latest()
        ->get();

        return response()->json([
            'blocked_keywords' => BlockKeywordResource::collection($blocks),
        ]);
    }

    public function store(BlockKeywordRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_blocked'] = true;
        $data['form_id'] = $request->form_id;

        $block = BlockKeyword::create($data);

        return response()->json([
            'message' => 'Keywords blocked successfully.',
            'data' => new BlockKeywordResource($block),
        ]);
    }

    public function getWebsites()
    {
        $user = auth()->user();

        $websites = Website::where('user_id', $user->id)
            ->where('status', 'active')
            ->get(['id', 'website_name as name']);

        return response()->json(['websites' => $websites]);
    }

    public function getForms($websiteId)
    {
        $user = auth()->user();

        $forms = CustomerForm::where('user_id', $user->id)
            ->where('website_id', $websiteId)
            ->where('status', 'active')
            ->get(['id', 'form_name']);

        return response()->json(['forms' => $forms]);
    }


    public function getKeywords()
    {
        $keywords = FormKeyword::where('status', 'active')->get(['id', 'keyword']);
        return response()->json(['keywords' => $keywords]);
    }

    public function getUsers()
    {
        $users = User::where('user_type', 'customer')
            ->where('status', 'active')
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->map(function ($user) {
                $user->fullname = $user->first_name . ' ' . $user->last_name;
                return $user;
            });

        return response()->json(['users' => $users]);
    }
}
