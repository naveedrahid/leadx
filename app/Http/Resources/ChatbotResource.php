<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'user_id'              => $this->user_id,
            'website_id'           => $this->website_id,
            'name'                 => $this->name,
            'slug'                 => $this->slug,
            'is_active'            => $this->is_active,
            'system_prompt'        => $this->system_prompt,
            'bubble_message'       => $this->bubble_message,
            'welcome_message'      => $this->welcome_message,
            'connect_message'      => $this->connect_message,
            'offline_message'      => $this->offline_message,
            'interaction_type'     => $this->interaction_type,
            'language'             => $this->language,
            'do_not_go_beyond'     => $this->do_not_go_beyond,
            'model'                => $this->model,
            'temperature'          => $this->temperature,
            'top_k'                => $this->top_k,
            'confidence_threshold' => $this->confidence_threshold,
            'avatar_type'          => $this->avatar_type,
            'avatar_url'           => $this->avatar_url,
            'logo_url'             => $this->logo_url,
            'color_accent'         => $this->color_accent,
            'show_logo'            => $this->show_logo,
            'show_datetime'        => $this->show_datetime,
            'transparent_trigger'  => $this->transparent_trigger,
            'trigger_avatar_size'  => $this->trigger_avatar_size,
            'position'             => $this->position,
            'footer_link'          => $this->footer_link,
            'custom_css'           => $this->custom_css,
            'settings'             => $this->settings,
            'public_token'         => $this->public_token,
            'iframe_width'         => $this->iframe_width,
            'iframe_height'        => $this->iframe_height,
            'domain_allowlist'     => $this->domain_allowlist,
            'moderation_on'        => $this->moderation_on,
            'qa_count'             => $this->whenCounted('qaPairs'),
            'created_at'           => optional($this->created_at)->toISOString(),
            'updated_at'           => optional($this->updated_at)->toISOString(),
        ];
    }
}
