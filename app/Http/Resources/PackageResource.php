<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stripe_product_id' => $this->stripe_product_id,
            'stripe_price_id' => $this->stripe_price_id,
            'title' => $this->title,
            'duration' => $this->duration,
            'duration_type' => $this->duration_type,
            'duration_lifetime' => $this->duration_lifetime,
            'trial_period_days' => $this->trial_period_days,
            'free_package' => $this->free_package,
            'price' => $this->price,
            '_price' => price_format($this->price),
            'regular_price' => $this->regular_price,
            '_regular_price' => price_format($this->regular_price),
            'sale_price' => $this->sale_price,
            '_sale_price' => price_format($this->sale_price),
            'plans' => $this->plans,
            'is_website_unlimited' => $this->is_website_unlimited,
            'website_limit' => $this->website_limit,
            'lead_limit' => $this->lead_limit,
            'unlimited_leads' => $this->unlimited_leads,
            'description' => $this->description,
            'sort' => $this->sort,
            'is_featured' => $this->is_featured,
            'is_public' => $this->is_public,
            'pro_fields' => $this->pro_fields,
            'form_restrictions' => $this->form_restrictions,
            'status' => $this->status,
            'subscription_count' => $this->subscrptions()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
