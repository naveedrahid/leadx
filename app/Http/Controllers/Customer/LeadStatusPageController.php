<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadStatusPageController extends Controller
{
    public function index(){
        return Inertia::render('Customer/LeadStatus/Index');
    }
    
    public function create(){
        return Inertia::render('Customer/LeadStatus/Create');
    }
    
    public function edit($id){
        $status = LeadStatus::findOrFail($id);
        return Inertia::render('Customer/LeadStatus/Edit', [
            'status' => $status
        ]);
    }
}
