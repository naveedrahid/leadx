<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiPaginate;
use App\Models\Plugin;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\{
    PluginStoreRequest,
    PluginUpdateRequest,
    PluginStatusRequest
};
use Illuminate\Support\Facades\DB;
use Stripe\Plan;

class PluginController extends Controller
{
    use ApiPaginate;

    public function get_count(Request $request)
    {
        $count = Plugin::filterPlugins($request)->count();
        return response()->json([
            "error" => 0,
            "data" => [
                "count" => $count
            ],
            "message" => "Plugin count have been successfully retrieved"
        ], 200);
    }

    public function get_all(Request $request)
    {
        $order = (object) [
            'orderby' => $request->filled('orderby') ? $request->orderby : 'id',
            'order' => $request->filled('order') ? $request->order : 'DESC',
        ];

        $pluginQuery = Plugin::filterPlugins($request)->orderBy($order->orderby, $order->order);

        if ($request->filled('perpage')) {
            $plugins = $pluginQuery->paginate($request->perpage);
        } else {
            if($request->filled('limit')) {
                $pluginQuery->limit($request->limit);
            }

            $plugins = $pluginQuery->get();
        }

        $response = [
            "error" => 0,
            "data" => $request->filled('perpage') ? $plugins->items() : $plugins,
            "message" => "Plugins have been successfully retrieved"
        ];

        if($request->filled('perpage')) {
            $response['paginate'] = $this->paginate($plugins);
        }
        return response()->json($response, 200);
    }

    public function get_by(Request $request, $id)
    {
        $plugin = Plugin::whereId($id)->first();
        if (is_null($plugin)) {
            return response()->json([
                "error" => 1,
                "message" => "Plugin Not Found!"
            ], 404);
        }

        return response()->json([
            "error" => 0,
            "data" => $plugin,
            "message" => "Plugin have been successfully retrieved"
        ], 200);
    }

    public function store(PluginStoreRequest $request)
    {
        $filename = null;
        if ($request->hasFile('plugin_file')) {
            $file = $request->file('plugin_file');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/plugins', $filename);
        }

        $plugin = Plugin::create([
            "plugin_name" => $request->plugin_name,
            "plugin_url" => $request->plugin_url,
            "version" => $request->version,
            "plugin_file" => $filename,
            "documentation" => $request->documentation,
            "status" => "active"
        ]);
        
        return response()->json([
            "error" => 0,
            "data" => $plugin,
            "message" => "Plugin has been successfully created"
        ], 200);
    }

    public function update(PluginUpdateRequest $request, $id)
    {
        $plugin = Plugin::whereId($id)->first();
        if (is_null($plugin)) {
            return response()->json([
                "error" => 1,
                "message" => "Plugin Not Found!"
            ], 404);
        }

        $filename = $plugin->plugin_file;
        if($request->has('remove_plugin_file')) {
            if (Storage::exists('public/plugins/' . $plugin->plugin_file)) {
                Storage::delete('public/plugins/' . $plugin->plugin_file);
            }

            $filename = null;
        }

        if ($request->hasFile('plugin_file')) {
            $file = $request->file('plugin_file');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/plugins', $filename);
            
            if (Storage::exists('public/plugins/' . $plugin->plugin_file)) {
                Storage::delete('public/plugins/' . $plugin->plugin_file);
            }
        }

        $plugin->update([
            "plugin_name" => $request->plugin_name,
            "plugin_url" => $request->plugin_url,
            "version" => $request->version,
            "plugin_file" => $filename,
            "documentation" => $request->documentation
        ]);

        return response()->json([
            "error" => 0,
            "data" => $plugin,
            "message" => "Plugin has been successfully updated"
        ], 200);
    }

    public function status(PluginStatusRequest $request, $id)
    {
        $plugin = Plugin::whereId($id)->first();
        if (is_null($plugin)) {
            return response()->json([
                "error" => 1,
                "message" => "Plugin Not Found!"
            ], 404);
        }

        $plugin->update([
            "status" => $request->status
        ]);

        return response()->json([
            "error" => 0,
            "data" => $plugin,
            "message" => "Plugin status has been successfully updated"
        ], 200);
    }

    public function delete(Request $request, $id)
    {
        $plugin = Plugin::whereId($id)->first();
        if (is_null($plugin)) {
            return response()->json([
                "error" => 1,
                "message" => "Plugin Not Found!"
            ], 404);
        }

        if ($plugin->plugin_file) {
            if (Storage::exists('public/plugins/' . $plugin->plugin_file)) {
                Storage::delete('public/plugins/' . $plugin->plugin_file);
            }
        }
        
        $plugin->delete();

        return response()->json([
            "error" => 0,
            "message" => "Plugin has been successfully deleted"
        ], 200);
    }
}
