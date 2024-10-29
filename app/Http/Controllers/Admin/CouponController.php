<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CouponController extends Controller
{
    public function index() : Response
    {
        return Inertia::render('Admin/Coupon/Index');
    }

    public function create() : Response
    {
        return Inertia::render('Admin/Coupon/Create');
    }

    public function edit($id) : Response
    {
        return Inertia::render('Admin/Coupon/Edit', [
            'id' => $id
        ]);
    }

    public function show($id) : Response
    {
        return Inertia::render('Admin/Coupon/Show', [
            'id' => $id
        ]);
    }
}
