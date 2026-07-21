<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('page'));
    }

    public function rules(): array
    {
        return [
            'title'        => ['sometimes', 'string', 'max:255'],
            'body'         => ['sometimes', 'string'],
            'menu_item_id' => ['sometimes', 'integer', 'exists:menu_items,id'],
            'cover_image'  => ['nullable', 'image', 'max:2048'],
            'status'       => ['sometimes', 'in:draft,published'],
        ];
    }
}
