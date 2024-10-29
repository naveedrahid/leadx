<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'admin';

    public function __construct()
    {
        if(request()->is('app/*')){
            $this->rootView = 'admin';
        } else {
            $this->rootView = 'frontend';
        }
    }

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'app' => [
                'name' => config('app.name'),
                'url' => config('app.url'),
                'api_url' => config('app.api_url'),
                'support_mail' => config('app.support_mail'),
                'support_number' => config('app.support_number'),
            ],
            'stripe_key' => config('services.stripe.key'),
            'currency_symbol' => currency_symbol(),
            'currency_code' => currency_code(),
            'is_admin' => (request()->routeIs('app.admin.*')) ? true : false,
            'is_customer' => (request()->routeIs('app.customer.*')) ? true : false
        ]);
    }
}
