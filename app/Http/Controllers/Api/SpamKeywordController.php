<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormCategory;
use App\Traits\ApiPaginate;
use Illuminate\Http\Request;

class SpamKeywordController extends Controller
{

    use ApiPaginate;

    public function get_all(Request $request)
    {
        $order = (object)[
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $formQuery = FormCategory::orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $form = $formQuery->paginate($request->perpage);
        } else {
            if ($request->filled('limit')) {
                $formQuery->limit($request->limit);
            }
            $form = $formQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $form->items() : $form,
            "message" => "Leads have been successfully retrieved"
        ];

        dd($response);
        if ($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($form);
        }
        return response()->json($response, 200);
    }
}
