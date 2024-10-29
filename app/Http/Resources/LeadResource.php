<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "user" => new UserResource($this->whenLoaded('user')),
            "website" => new WebsiteResource($this->whenLoaded('website')),
            "wpform_id" => $this->wpform_id,
            "wpform_name" => $this->wpform_name,
            "uuid" => $this->uuid,
            "form_data" => $this->form_data,
            "is_viewed" => $this->is_viewed,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}