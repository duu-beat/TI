<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\TicketPriority;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'string', 'max:50'], // 👈 Adicionado
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['nullable', Rule::enum(TicketPriority::class)],
            
            // ✨ ATUALIZADO PARA MÚLTIPLOS ARQUIVOS
            'attachments' => ['nullable', 'array', 'max:5'], // Máx 5 arquivos (opcional)
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB cada
        ];
    }
}