<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'stripe_coupon_id' => $this->stripe_coupon_id,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'amount' => $this->amount,
            'discount' => $this->discount,
            'max_uses' => $this->max_uses,
            'max_uses_user' => $this->max_uses_user,
            'duration' => $this->duration,
            'duration_month' => $this->duration_month,
            'expires_at' => $this->expires_at,
            'status' => $this->status,
            'subscription_count' => $this->subscriptions()->count(),
            'uses' => $this->users()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}