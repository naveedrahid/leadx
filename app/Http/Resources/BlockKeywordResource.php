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
            'form_id' => optional($this->form)->form_name ?? 'N/A',
            'website' => optional($this->website)->website_name ?? 'N/A',
            'keywords' => is_array($this->keywords) ? $this->keywords : json_decode($this->keywords),
            'is_blocked' => (bool) $this->is_blocked,
            'created_at' => $this->created_at->format('d M Y'),
        ];
    }
}
