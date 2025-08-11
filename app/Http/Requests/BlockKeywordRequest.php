<?php

namespace App\Http\Requests;

use App\Models\BlockKeyword;
use App\Models\CustomerForm;
use App\Models\FormKeyword;
use Illuminate\Foundation\Http\FormRequest;

class BlockKeywordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function prepareForValidation(): void
    {
        // 1) Update mode detect (route param se), chahe method POST ho
        $blockId = $this->route('block_keyword')
            ?? $this->route('blockKeyword')
            ?? $this->route('id')
            ?? (is_object($this->route()?->parameter('block_keyword') ?? null)
                ? optional($this->route()->parameter('block_keyword'))->id
                : null);

        $block = $blockId ? BlockKeyword::find($blockId) : null;

        // 2) Agar update hai aur website_id/form_id nahi aaye to current record se merge
        if ($block) {
            if (!$this->has('website_id')) {
                $this->merge(['website_id' => $block->website_id]);
            }
            if (!$this->has('form_id')) {
                $this->merge(['form_id' => $block->form_id]);
            }
        }

        // 3) WP id mapping (aapka existing logic)
        $incoming = $this->input('form_id');
        if (is_numeric($incoming)) {
            $wpId = null;
            $existsAsWp = CustomerForm::where('form_id', (int)$incoming)->exists();
            if ($existsAsWp) {
                $wpId = (int)$incoming;
            } else {
                $cf = CustomerForm::find($incoming);
                if ($cf && $cf->form_id) $wpId = (int)$cf->form_id;
            }
            if ($wpId !== null) $this->merge(['form_id' => $wpId]);
        }

        // 4) Keywords normalize (array/string), new text ko ID me convert
        $kwInput = $this->input('keywords', []);
        if (!is_array($kwInput)) $kwInput = [$kwInput];

        // Optional: agar UI se new text field aa rahi ho
        $newText = trim((string)$this->input('new_keyword', ''));
        if ($newText !== '') $kwInput[] = $newText;

        $ids = [];
        foreach ($kwInput as $k) {
            if ($k === null || $k === '') continue;

            if (is_int($k) || (is_string($k) && ctype_digit($k))) {
                $id = (int)$k;
                if ($id > 0) $ids[] = $id;
                continue;
            }

            $kw = trim((string)$k);
            if ($kw === '') continue;
            $fk = FormKeyword::firstOrCreate(
                ['keyword' => $kw],
                ['created_by' => optional($this->user())->id, 'status' => 'active']
            );
            $ids[] = (int)$fk->id;
        }
        $ids = array_values(array_unique(array_map('intval', $ids)));

        $this->merge(['keywords' => $ids, '_is_update' => (bool)$block]);
    }

    public function rules(): array
    {
        // Update detect robustly (route id ya model param ho to)
        $isUpdate = (bool)($this->input('_is_update', false)
            || $this->route('block_keyword')
            || $this->route('blockKeyword')
            || $this->route('id'));

        return [
            // Update pe required nahi — prepareForValidation ne merge kar diya hoga
            'website_id' => [$isUpdate ? 'sometimes' : 'required', 'integer', 'exists:websites,id'],
            'form_id'    => [$isUpdate ? 'sometimes' : 'required', 'integer'],

            // Create: at least 1; Update: present (empty allow, clear karne ke liye)
            'keywords'   => [$isUpdate ? 'present' : 'required', 'array'],
            'keywords.*' => ['integer', 'distinct', 'exists:form_keywords,id'],
        ];
    }
}
