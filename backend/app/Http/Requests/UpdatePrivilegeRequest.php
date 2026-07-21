<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePrivilegeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('privilege'));
    }

    public function rules(): array
    {
        return [
            'key'         => ['sometimes', 'string', 'max:255', Rule::unique('privileges', 'key')->ignore($this->route('privilege'))],
            'description' => ['sometimes', 'string', 'max:255'],
        ];
    }
}
