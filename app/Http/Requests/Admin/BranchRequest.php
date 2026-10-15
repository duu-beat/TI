<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        $branch = $this->route('branch');
        $branchId = is_object($branch) ? $branch->getKey() : $branch;

        return [
            'name' => ['required', 'string', 'max:150', Rule::unique('branches', 'name')->ignore($branchId)],
            'code' => ['nullable', 'string', 'max:30', Rule::unique('branches', 'code')->ignore($branchId)],
            'address' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
