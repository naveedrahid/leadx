<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlockedIPController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Customer/BlockedIP/index');
    }


}
