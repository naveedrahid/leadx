<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FormKeywordStoreRequest;
use App\Http\Resources\FormKeywordResource;
use App\Models\FormKeyword;
use Illuminate\Http\Request;

class FormKeywordController extends Controller
{
    public function index(Request $request)
    {
        $query = FormKeyword::query();

        $query->where('created_by', auth()->id());

        if ($request->filled('search')) {
            $query->where('keyword', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return FormKeywordResource::collection(
            $query->latest()->paginate($request->perpage ?? 10)
        );
    }

    public function store(FormKeywordStoreRequest $request)
    {
        $keyword = FormKeyword::create([
            'keyword' => $request->keyword,
            'created_by' => auth()->id(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'data' => new FormKeywordResource($keyword),
            'message' => 'Keyword created successfully',
        ]);
    }

    public function edit($id)
    {
        $keyword = FormKeyword::findOrFail($id);
        return new FormKeywordResource($keyword);
    }

    public function update(FormKeywordStoreRequest $request, $id)
    {
        $keyword = FormKeyword::findOrFail($id);
        $keyword->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new FormKeywordResource($keyword),
            'message' => 'Keyword updated successfully.'
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $keyword = FormKeyword::findOrFail($id);
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        $keyword->status = $request->status;
        $keyword->save();

        return response()->json([
            'message' => 'Status updated successfully.',
        ]);
    }
}
