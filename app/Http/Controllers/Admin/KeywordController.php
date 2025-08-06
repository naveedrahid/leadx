<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormKeyword;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KeywordController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Keywords/index');
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Keywords/Create');
    }

    public function edit($id)
    {
        $keyword = FormKeyword::findOrFail($id);
        return Inertia::render('Admin/Keywords/Edit', [
            'keyword' => $keyword
        ]);
    }
}
