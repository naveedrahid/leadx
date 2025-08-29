<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChatbotController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Customer/Chatbot/Index');
    }

    public function create(): Response
    {
        return Inertia::render('Customer/Chatbot/Create');
    }
}
