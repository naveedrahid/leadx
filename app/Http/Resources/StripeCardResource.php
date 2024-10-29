<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StripeCardResource extends JsonResource
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
            'user' => $this->user,
            'pm_id' => $this->pm_id,
            'brand' => $this->brand,
            'last4' => $this->last4,
            'default' => $this->default,
            'exp_month' => $this->exp_month,
            'exp_year' => $this->exp_year,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
