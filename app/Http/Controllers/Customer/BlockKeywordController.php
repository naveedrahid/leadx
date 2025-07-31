<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}
