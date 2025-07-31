<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerFormRequest;
use App\Models\CustomerForm;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        parse_str($request->getContent(), $data);
        $website = Website::where('website_url', 'LIKE', '%' . ($data['website_url'] ?? '') . '%')->status('active')->first();

        if (!$website) {
            return response()->json(['error' => 'Website not found or inactive'], 404);
        }
        $formId = isset($data['form_id']) ? (int) $data['form_id'] : null;

        $customerForm = CustomerForm::create([
            'user_id' => $website->user_id,
            'form_id' => $formId,
            'form_name' => $data['form_name'] ?? '',
            'form_key' => $data['form_key'] ?? '',
            'website_id' => $website?->id ?? null,
            'template' => $data['template'] ?? '',
            'custom_css' => $data['custom_css'] ?? '',
            'settings' => $data['settings'] ?? '',
        ]);

        return response()->json(['success' => true, 'data' => $customerForm], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
