<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SpamKeywordController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Customer/Spam_keyword/index');
    }


}
