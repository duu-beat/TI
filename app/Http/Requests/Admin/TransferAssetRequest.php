<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', User::ROLE_CLIENT))],
            'branch_id' => ['nullable', 'integer', Rule::exists('branches', 'id')->whereNull('deleted_at')],
            'sector_id' => [
                'nullable',
                'integer',
                Rule::exists('sectors', 'id')->where(fn ($query) => $query
                    ->where('branch_id', $this->input('branch_id'))
                    ->whereNull('deleted_at')),
            ],
            'reason' => ['required', 'string', 'max:500'],
        ];
    }
}
