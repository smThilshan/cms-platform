<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('role'));
    }

    public function rules(): array
    {
        return [
            'name'            => ['sometimes', 'string', 'max:255'],
            'slug'            => ['sometimes', 'string', 'max:255', Rule::unique('roles', 'slug')->ignore($this->route('role'))],
            'privilege_ids'   => ['nullable', 'array'],
            'privilege_ids.*' => ['integer', 'exists:privileges,id'],
        ];
    }
}
