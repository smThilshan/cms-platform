<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('menu_item'));
    }

    public function rules(): array
    {
        return [
            'title'     => ['sometimes', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'order'     => ['nullable', 'integer', 'min:0'],
        ];
    }
}
