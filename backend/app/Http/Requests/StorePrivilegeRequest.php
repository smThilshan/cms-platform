<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrivilegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Privilege::class);
    }

    public function rules(): array
    {
        return [
            'key'         => ['required', 'string', 'max:255', 'unique:privileges,key'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }
}
