<?php

namespace App\Http\Requests;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // A rota já tem middleware 'auth', então permitimos
    }

    public function rules(): array
{
    return [
        'subject' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'priority' => ['nullable', \Illuminate\Validation\Rule::enum(\App\Enums\TicketPriority::class)],
        // Aceita imagem ou PDF, máx 2MB
        'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'], 
    ];
}
}