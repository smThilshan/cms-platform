<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Page::class);
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'body'         => ['required', 'string'],
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'cover_image'  => ['nullable', 'image', 'max:2048'],
            'status'       => ['required', 'in:draft,published'],
        ];
    }
}
