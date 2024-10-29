<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WebsiteController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Customer/Website/Index');
    }

    public function create() : Response
    {
        return Inertia::render('Customer/Website/Create');
    }

    public function edit($id) : Response
    {
        return Inertia::render('Customer/Website/Edit', [
            'id' => $id
        ]);
    }
}
