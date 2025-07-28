<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadStatusResource;
use App\Models\LeadStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = LeadStatus::query();

        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('dates') && is_array($request->dates)) {
            $start = Carbon::parse($request->dates[0])->startOfDay();
            $end = Carbon::parse($request->dates[1])->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }
        
        return LeadStatusResource::collection(
            $query->latest()->paginate($request->perpage ?? 10)
        );
    }

    // public function index(Request $request)
    // {
    //     $query = LeadStatus::query();

    //     if ($request->has('search') && !empty($request->search)) {
    //         $query->where('name', 'like', '%' . $request->search . '%');
    //     }

    //     if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
    //         $query->where('status', $request->status);
    //     }

    //     $query->orderBy($request->orderby ?? 'id', $request->order ?? 'desc');

    //     $data = $query->paginate($request->perpage ?? 10);

    //     return LeadStatusResource::collection(
    //         LeadStatus::query()
    //             ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
    //             ->when($request->status, fn($q) => $q->where('status', $request->status))
    //             ->paginate($request->perpage ?? 10)
    //     );
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:lead_statuses,name',
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $status = LeadStatus::create($validated);
        return new LeadStatusResource($status);
    }

    /**
     * Display the specified resource.
     */
    public function show(LeadStatus $leadStatus)
    {
        return new LeadStatusResource($leadStatus);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeadStatus $leadStatus)
    {
        $validated = $request->validate([
            'name'   => [
                'required',
                'string',
                'max:255',
                Rule::unique('lead_statuses', 'name')->ignore($leadStatus->id),
            ],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $leadStatus->update($validated);
        return new LeadStatusResource($leadStatus);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeadStatus $leadStatus)
    {
        $leadStatus->delete();
        return response()->json(['message' => 'Lead Status deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 422);
        }

        LeadStatus::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Selected lead statuses deleted successfully']);
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $leadStatus = LeadStatus::find($id);

        if (!$leadStatus) {
            return response()->json([
                'message' => 'Lead status not found.',
            ], 404);
        }

        $leadStatus->status = $request->status;
        $leadStatus->save();

        return response()->json([
            'message' => 'Status updated successfully.',
            'status' => $leadStatus->status,
        ]);
    }
}
