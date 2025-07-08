<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function show() : Response
    {
        return Inertia::render('Auth/Login', [
            'pricingUrl' => url('/#pricing'),
            'app' => [
                'name' => config('app.name'),
            ],
        ]);
    }
}
