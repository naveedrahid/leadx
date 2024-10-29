<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PackageController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Admin/Package/Index');
    }

    public function create() : Response
    {
        return Inertia::render('Admin/Package/Create');
    }

    public function edit($id) : Response
    {
        return Inertia::render('Admin/Package/Edit', [
            'id' => $id
        ]);
    }

    public function show($id) : Response
    {
        return Inertia::render('Admin/Package/Show', [
            'id' => $id
        ]);
    }
}
