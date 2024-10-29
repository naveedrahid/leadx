<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PluginController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Admin/Plugin/Index');
    }

    public function create() : Response
    {
        return Inertia::render('Admin/Plugin/Create');
    }

    public function edit($id) : Response
    {
        return Inertia::render('Admin/Plugin/Edit', [
            'id' => $id
        ]);
    }
}
