<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'fullname' => $this->fullname,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name_chars' => $this->name_chars,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'profile_image' => $this->profile_image_url,
            'avatar_color' => $this->avatar_color,
            'status' => $this->status,
            'is_admin' => $this->is_admin,
            'avail_free_package' => $this->avail_free_package,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
        
        if($this->is_admin == false) {
            $subscription_active = $this->subscriptions()->status(['active', 'trialing'])->exists() ? true : false;
            $data['subscription_active'] = $subscription_active;
            $data['stripe_id'] = $this->stripe_id;
            $data['stripe_card'] = $this->stripe_cards()->where('default', 1)->first();
            $data['trial'] = $this->trial;
            $data['free_package'] = $this->avail_free_package;
        }

        return $data;
    }
}
