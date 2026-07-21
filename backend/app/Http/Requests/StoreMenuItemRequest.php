<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\MenuItem::class);
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
            'order'     => ['nullable', 'integer', 'min:0'],
        ];
    }
}
