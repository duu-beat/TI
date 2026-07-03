<?php

namespace App\Http\Requests\Client;

use App\Enums\TicketPriority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Middleware auth já cuida disso
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string'],
            'priority' => ['required', Rule::enum(TicketPriority::class)],
            'description' => ['required', 'string'],
            'asset_id' => ['nullable', 'exists:assets,id'],
            'attachments.*' => ['nullable', 'file', 'max:10240'], // 10MB
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'O assunto é obrigatório.',
            'category.required' => 'A categoria é obrigatória.',
            'priority.required' => 'A prioridade é obrigatória.',
            'description.required' => 'A descrição do problema é obrigatória.',
            'asset_id.exists' => 'O equipamento selecionado é inválido.',
        ];
    }
}
