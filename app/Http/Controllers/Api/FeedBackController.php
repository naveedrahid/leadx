<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Http\Requests\FeedBackBulkDeleteRequest;
use App\Models\{
    FeedBack,
    User
};
use Illuminate\Support\Facades\DB;

class FeedBackController extends Controller
{
    use ApiPaginate;

    public function resolveUser(Request $request) {
        if($request->filled('user_id')) {
            return User::whereId($request->user_id)->first();
        } else {
            return $request->user();
        }
    }

    public function get_count(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $count = FeedBack::filterFeedBack($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Feedback count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $feedbacksQuery = FeedBack::filterFeedBack($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $feedbacks = $feedbacksQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $feedbacksQuery->limit($request->limit);
            }

            $feedbacks = $feedbacksQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $feedbacks->items() : $feedbacks,
            "message" => "Feedback have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($feedbacks);
        }
        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $feedback = FeedBack::whereId($id)->first();
        if (is_null($feedback)) {
            return response()->json([
                "error" => 1,
                "message" => "Feedback Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $feedback,
            "message" => "Feedback have been successfully retrieved"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $feedback = FeedBack::whereId($id)->first();
        if (is_null($feedback)) {
            return response()->json([
                "error" => 1,
                "message" => "Feedback Not Found!"
            ], 404);
        }

        $feedback->delete();

        return response()->json([
            "error" => 0,
            "message" => "Feedback has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(FeedBackBulkDeleteRequest $request)
    {
        $user = $this->resolveUser($request);
        if(is_null($user)) {
            return response()->json([
                "error" => 1,
                "message" => "Access Denied!"
            ], 404);
        }

        $feedbacks = FeedBack::whereIn('id', $request->ids)->get();
        if (!$feedbacks->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Feedbacks Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($feedbacks as $feedback) {
            try {
                DB::beginTransaction();

                $feedback->delete();
                $deletedCount++;
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $feedback->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Feedbacks deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' feedbacks.';
        }

        return response()->json([
            'error' => 0,
            'data' => [
                'failed_items' => $failedToDelete
            ],
            'message' => $message
        ], 200);
    }
}
