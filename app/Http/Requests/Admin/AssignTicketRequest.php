<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'assigned_to' => ['required', Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', [User::ROLE_ADMIN, User::ROLE_MASTER]))],
        ];
    }
}
