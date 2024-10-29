<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'stripe_subscription_id' => $this->stripe_subscription_id,
            'stripe_customer_id' => $this->stripe_customer_id,
            'stripe_plan_id' => $this->stripe_plan_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'package' => new PackageResource($this->whenLoaded('package')),
            'websites' => WebsiteResource::collection($this->whenLoaded('websites')),
            'coupon' => new CouponResource($this->whenLoaded('coupon')),
            'coupon_expire_at' => $this->whenLoaded('coupon') ? $this->coupon_expire_at : null,
            'name' => $this->name,
            'amount' => $this->amount,
            '_amount' => price_format($this->amount),
            'payment_method' => $this->payment_method,
            'next_billing_date' => $this->next_billing_date,
            'trial_start_at' => $this->trial_start_at,
            'trial_end_at' => $this->trial_end_at,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'status' => $this->status,
            'payload' => $this->payload,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
