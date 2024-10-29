<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'package' => new PackageResource($this->whenLoaded('package')),
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
            'coupon' => new CouponResource($this->whenLoaded('coupon')),
            'coupon_expire_at' => $this->whenLoaded('coupon') ? $this->coupon_expire_at : null,
            'name' => $this->name,
            'status' => $this->status,
            'amount' => $this->amount,
            '_amount' => price_format($this->amount),
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
