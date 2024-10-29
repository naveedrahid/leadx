<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    public function show(Request $request) : Response
    {
        return Inertia::render('Auth/Register', [
            'is_admin' => $request->has('admin') ? 1 : 0
        ]);
    }
}
