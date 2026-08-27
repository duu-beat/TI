<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:50000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,webp,pdf,txt,doc,docx,xls,xlsx,zip', 'max:10240'],
            'is_internal' => ['sometimes', 'boolean'],
            'time_spent' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }
}
