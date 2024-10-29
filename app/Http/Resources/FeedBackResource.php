<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeedBackResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'ip_address' => $this->ip_address,
            'country_code' => $this->country_code,
            'country_name' => $this->country_name,
            'city' => $this->city,
            'region' => $this->region,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'timezone' => $this->timezone,
            'continent_code' => $this->continent_code,
            'continent_name' => $this->continent_name,
            'currency_code' => $this->currency_code,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
