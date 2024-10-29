<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResetPasswordController extends Controller
{
    public function show(Request $request, $token) : Response
    {
        return Inertia::render('Auth/ResetPassword', [
            'reset_token' => $token,
            'email' => $request->email
        ]);
    }
}
