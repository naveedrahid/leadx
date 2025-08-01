<?php

namespace App\Http\Resources;

use App\Models\CustomerForm;
use App\Models\FormKeyword;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlockKeywordResource extends JsonResource
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
            'website_id' => $this->website_id,
            'form_id' => $this->form_id,
            'keywords' => FormKeyword::whereIn('id', $this->keywords ?? [])->get(['id', 'keyword']),
            'user_id' => $this->user_id,
            'is_blocked' => (bool) $this->is_blocked,
            'website' => $this->website ? [
                'id' => $this->website->id,
                'website_name' => $this->website->website_name
            ] : null,
            'form' => $this->form ? [
                'id' => $this->form->id,
                'form_name' => $this->form->form_name
            ] : null,
            'created_at' => optional($this->created_at)->format('Y-m-d H:i'),
        ];
    }
}
