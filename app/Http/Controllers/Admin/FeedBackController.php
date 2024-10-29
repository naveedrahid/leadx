<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FeedBackController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Admin/FeedBack/Index');
    }

    public function show($id) : Response
    {
        return Inertia::render('Admin/FeedBack/Show', [
            'id' => $id
        ]);
    }
}
