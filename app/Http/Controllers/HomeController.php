<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function home() : Response
    {
        return Inertia::render('Home');
    }

    public function homeDemo() : Response
    {
        return Inertia::render('HomeDemo');
    }

    public function privacy_policy(){
        return Inertia::render('Privacy-policy');
    }

    public function customerReviews(){
        return Inertia::render('CustomerReviews');
    }

    public function contact(){
        return Inertia::render('Contact');
    }

    public function terms_conditions(){
        return Inertia::render('Terms-conditions');
    }

    public function pricing() : Response
    {
        return Inertia::render('Pricing');
    }

    public function featured() : Response
    {
        return Inertia::render('Featured');
    }
}
