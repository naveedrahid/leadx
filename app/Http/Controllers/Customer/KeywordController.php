<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\FormKeyword;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class KeywordController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/Keywords/index');
    }

    public function create(): Response
    {
        return Inertia::render('Customer/Keywords/Create');
    }

    public function edit($id)
    {
        $keyword = FormKeyword::findOrFail($id);
        return Inertia::render('Customer/Keywords/Edit', [
            'keyword' => $keyword
        ]);
    }
}
