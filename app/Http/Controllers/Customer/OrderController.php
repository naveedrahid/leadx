<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/Orders/Index');
    }

    public function show(Order $order): Response
    {
        return Inertia::render('Customer/Orders/Show', [
            'order'   => $order
        ]);
    }
}
