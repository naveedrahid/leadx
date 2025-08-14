<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $orders = Order::with('website')->where('user_id', $userId)->get();

        if ($orders->isEmpty()) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'ok'     => true,
            'orders' => $orders,
        ]);
    }

    public function show(Order $order)
    {
        return response()->json(['ok' => true, 'order' => $order]);
    }

    public function checkout(Request $request)
    {
        $secret =  config('services.woocommerce.webhook_secret', '');
        $sig    =  $request->header('X-WC-Webhook-Signature', '');
        $raw    =  $request->getContent();
        $calc   = base64_encode(hash_hmac('sha256', $raw, $secret, true));

        $sourceUrl =  $request->header('X-WC-Webhook-Source', '');
        $host = $this->normalizeHost($sourceUrl);

        $website = Website::whereRaw("REPLACE(LOWER(website_url), 'www.', '') LIKE ?", ["%{$host}%"])
            ->status('active')
            ->first();

        if (!$website) {
            return response()->json(['ok' => false, 'message' => 'Website not registered'], 404);
        }

        $payload     = $request->json()->all();
        $wooOrderId  = data_get($payload, 'id');
        $orderNumber = data_get($payload, 'number');

        $existing = null;
        if (!is_null($wooOrderId)) {
            $existing = Order::where('website_id', $website->id)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(order_data, '$.id')) = ?", [(string)$wooOrderId])
                ->first();
        } elseif (!empty($orderNumber)) {
            $existing = Order::where('website_id', $website->id)
                ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(order_data, '$.number')) = ?", [$orderNumber])
                ->first();
        }

        try {
            if ($existing) {
                $existing->update(['order_data' => $payload]);
                $order = $existing;
            } else {
                $order = Order::create([
                    'user_id'    => $website->user_id,
                    'website_id' => $website->id,
                    'form_id'    => null,
                    'order_data' => $payload,
                ]);
            }

            return response()->json([
                'ok'         => true,
                'order_id'   => $order->id,
                'website_id' => $order->website_id,
                'user_id'    => $order->user_id,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('woo.store_failed', ['err' => $e->getMessage(), 'woo_id' => $wooOrderId, 'host' => $host]);
            return response()->json(['ok' => false], 500);
        }
    }

    private function normalizeHost(string $urlOrHost): string
    {
        $h = parse_url($urlOrHost, PHP_URL_HOST) ?: $urlOrHost;
        $h = strtolower($h);
        if (str_starts_with($h, 'www.')) $h = substr($h, 4);
        return $h;
    }
}
