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
use Illuminate\Support\Facades\DB;

class BlockKeywordController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $blocks = BlockKeyword::with(['website:id,website_name', 'form:id,form_name'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $allKeywords = FormKeyword::where('status', 'active')
            ->get(['id', 'keyword'])
            ->keyBy('id');

        $data = $blocks->map(function ($block) use ($allKeywords) {
            return [
                'id' => $block->id,
                'website' => [
                    'id' => $block->website->id ?? null,
                    'website_name' => $block->website->website_name ?? 'N/A',
                ],
                'form_id' => $block->form_id,
                'form_name' => $block->form->form_name ?? 'N/A',
                'keywords' => collect($block->keywords ?? [])->map(function ($id) use ($allKeywords) {
                    return [
                        'id' => $id,
                        'keyword' => $allKeywords[$id]->keyword ?? 'Unknown',
                    ];
                }),
                'is_blocked' => $block->is_blocked,
                'created_at' => $block->created_at?->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'blocked_keywords' => $data,
        ]);
    }

    public function store(BlockKeywordRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['is_blocked'] = true;
        $data['form_id'] = $request->form_id;
        $exists = BlockKeyword::where('form_id', $data['form_id'])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'This form already has blocked keywords.',
            ], 422);
        }

        $block = BlockKeyword::create($data);

        return response()->json([
            'message' => 'Keywords blocked successfully.',
            'data' => new BlockKeywordResource($block),
        ]);
    }

    public function getCreatePageData()
    {
        $user = auth()->user();

        $websites = Website::where('user_id', $user->id)
            ->where('status', 'active')
            ->get(['id', 'website_name as name']);

        $selectedWebsite = $websites->first();

        $forms = collect();
        $keywords = collect();
        $defaultFormId = null;

        if ($selectedWebsite) {
            $forms = CustomerForm::where('user_id', $user->id)
                ->whereIn('website_id', $websites->pluck('id'))
                ->where('status', 'active')
                ->get(['id', 'form_name', 'website_id']);

            $selectedForm = $forms->first();
            $defaultFormId = $selectedForm?->id;

            $keywords = FormKeyword::where('status', 'active')
                ->get(['id', 'keyword']);
        }

        return response()->json([
            'websites' => $websites,
            'forms' => $forms,
            'keywords' => $keywords,
            'default_website_id' => $selectedWebsite?->id,
            'default_form_id' => $defaultFormId,
        ]);
    }


    // public function getWebsites()
    // {
    //     $user = auth()->user();

    //     $websites = Website::where('user_id', $user->id)
    //         ->where('status', 'active')
    //         ->get(['id', 'website_name as name']);

    //     return response()->json(['websites' => $websites]);
    // }

    // public function getForms($websiteId)
    // {
    //     $user = auth()->user();

    //     $forms = CustomerForm::where('user_id', $user->id)
    //         ->where('website_id', $websiteId)
    //         ->where('status', 'active')
    //         ->get(['id', 'form_name']);

    //     return response()->json(['forms' => $forms]);
    // }

    // public function getKeywords()
    // {
    //     $keywords = FormKeyword::where('status', 'active')->get(['id', 'keyword']);
    //     return response()->json(['keywords' => $keywords]);
    // }

    // public function getUsers()
    // {
    //     $users = User::where('user_type', 'customer')
    //         ->where('status', 'active')
    //         ->select('id', 'first_name', 'last_name')
    //         ->get()
    //         ->map(function ($user) {
    //             $user->fullname = $user->first_name . ' ' . $user->last_name;
    //             return $user;
    //         });

    //     return response()->json(['users' => $users]);
    // }

    public function toggleStatus(Request $request, $id)
    {
        $blockKeyword = BlockKeyword::findOrFail($id);
        $request->validate([
            'is_blocked' => 'required|boolean'
        ]);
        $blockKeyword->is_blocked = $request->is_blocked;
        $blockKeyword->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ]);
    }

    public function update(BlockKeywordRequest $request, $id)
    {
        $block = BlockKeyword::findOrFail($id);
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $block->update($data);

        return response()->json(['message' => 'Block updated successfully.']);
    }
}
