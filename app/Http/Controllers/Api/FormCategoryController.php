<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Models\FormCategory;
use App\Http\Requests\{
    FormCategoryStoreRequest,
    FormCategoryUpdateRequest,
    FormCategoryStatusUpdateRequest,
    FormCategoryBulkDeleteRequest
};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FormCategoryController extends Controller
{
    use ApiPaginate;
    
    public function get_count(Request $request)
    {
        $count = FormCategory::filterCategories($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Categories count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $formCategoriesQuery = FormCategory::filter($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $form_categories = $formCategoriesQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $formCategoriesQuery->limit($request->limit);
            }

            $form_categories = $formCategoriesQuery->get();
        }

        if($request->filled('templates')) {
            $form_categories->load('templates');
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $form_categories->items() : $form_categories,
            "message" => "Categories have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($form_categories);
        }

        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $form_category = FormCategory::whereId($id)->first();
        if (is_null($form_category)) {
            return response()->json([
                "error" => 1,
                "message" => "Category Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $form_category,
            "message" => "Category have been successfully retrieved"
        ], 200);
    }

    public function store(FormCategoryStoreRequest $request)
    {
        $slug = Str::slug($request->name, '-');
        $form_category = FormCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'status' => 'active'
        ]);

        return response()->json([
            "error" => 0,
            "data" => $form_category,
            "message" => "Category has been successfully created"
        ], 200);
    }

    public function update(FormCategoryUpdateRequest $request, $id)
    {
        $form_category = FormCategory::whereId($id)->first();
        if (is_null($form_category)) {
            return response()->json([
                "error" => 1,
                "message" => "Category Not Found!"
            ], 404);
        }

        $slug = Str::slug($request->name, '-');
        $form_category->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id
        ]);

        return response()->json([
            "error" => 0,
            "data" => $form_category,
            "message" => "Category has been successfully updated"
        ], 200);
    }

    public function status(FormCategoryStatusUpdateRequest $request, $id)
    {
        $form_category = FormCategory::whereId($id)->first();
        if (is_null($form_category)) {
            return response()->json([
                "error" => 1,
                "message" => "Category Not Found!"
            ], 404);
        }

        if($form_category->child_categories()->count()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't update the status of this."
            ], 404);
        }

        if($form_category->templates()->count()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't update the status of this."
            ], 404);
        }

        $form_category->update([
            'status' => $request->status
        ]);

        return response()->json([
            "error" => 0,
            "data" => $form_category,
            "message" => "Category status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $form_category = FormCategory::whereId($id)->first();
        if (is_null($form_category)) {
            return response()->json([
                "error" => 1,
                "message" => "Category Not Found!"
            ], 404);
        }

        if($form_category->child_categories()->count()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't delete this."
            ], 404);
        }

        if($form_category->templates()->count()) {
            return response()->json([
                "error" => 1,
                "message" => "You can't delete this."
            ], 404);
        }
        
        $form_category->delete();

        return response()->json([
            "error" => 0,
            "message" => "Category has been successfully deleted"
        ], 200);
    }

    public function bulk_delete(FormCategoryBulkDeleteRequest $request)
    {
        $form_categories = FormCategory::whereIn('id', $request->ids)->get();
        if (!$form_categories->count()) {
            return response()->json([
                "error" => 1,
                "message" => "Categories Not Found!"
            ], 404);
        }

        $deletedCount = 0;
        $failedToDelete = [];

        foreach($form_categories as $form_category) {
            try {
                DB::beginTransaction();

                if(!$form_category->templates()->count() && !$form_category->child_categories()->count()) {
                    $form_category->delete();
                    $deletedCount++;
                } else {
                    $failedToDelete[] = $form_category->id;
                }
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failedToDelete[] = $form_category->id;
            }
        }

        $message = '';
        if ($deletedCount > 0) {
            $message .= $deletedCount . ' Categories deleted successfully.';
        }

        if (count($failedToDelete) > 0) {
            $message .= ' Failed to delete ' . count($failedToDelete) . ' categories.';
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
