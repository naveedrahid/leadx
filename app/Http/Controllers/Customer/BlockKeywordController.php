<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlockKeywordResource;
use App\Models\BlockKeyword;
use App\Models\CustomerForm;
use App\Models\FormKeyword;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlockKeywordController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/Blockkeyword/Index');
    }

    public function create(): Response
    {
        $users = User::select('id', 'first_name', 'last_name')
            ->where('user_type', 'customer')
            ->where('status', 'active')
            ->get()
            ->map(function ($user) {
                $user->fullname = $user->first_name . ' ' . $user->last_name;
                return $user;
            });

        return Inertia::render('Customer/Blockkeyword/Create', [
            'users' => $users,
        ]);
    }

    public function edit($id)
    {
        $block = BlockKeyword::with(['website', 'form'])->findOrFail($id);

        $websites = Website::where('user_id', auth()->id())
            ->where('status', 'active')
            ->get(['id', 'website_name']);

        $forms = CustomerForm::where('user_id', auth()->id())
            ->where('website_id', $block->website_id)
            ->where('status', 'active')
            ->get(['id', 'form_name']);

        $selectedKeywordIds = is_array($block->keywords) ? $block->keywords : [];

        $selectedKeywords = FormKeyword::whereIn('id', $selectedKeywordIds)
            ->get(['id', 'keyword']);

        $availableKeywords = FormKeyword::where('status', 'active')
            ->whereNotIn('id', $selectedKeywordIds)
            ->get(['id', 'keyword']);

        $block->keywords = $selectedKeywords;

        return Inertia::render('Customer/Blockkeyword/Edit', [
            'block' => $block,
            'websites' => $websites,
            'forms' => $forms,
            'keywords' => $selectedKeywords,
            'allKeywords' => $availableKeywords->concat($selectedKeywords)->unique('id')->values(),
        ]);
    }
}
