<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function dashboard() : Response
    {
        return Inertia::render('Admin/Dashboard');
    }

    public function profile() : Response
    {
        return Inertia::render('Admin/Profile');
    }

    public function account_setting() : Response
    {
        return Inertia::render('Admin/AccountSetting');
    }

    public function change_password() : Response
    {
        return Inertia::render('Admin/ChangePassword');
    }
}
