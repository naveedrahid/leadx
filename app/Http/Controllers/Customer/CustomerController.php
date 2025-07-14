<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Customer/Lead_Detail/index');
    }
}
