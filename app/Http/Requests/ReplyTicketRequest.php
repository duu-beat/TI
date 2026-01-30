<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // A autorização principal do Ticket já é feita no Controller ou Rotas.
        // Aqui permitimos, desde que o user esteja logado.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
            // Regras de anexo idênticas ao StoreTicketRequest
            'attachments' => ['nullable', 'array', 'max:5'], 
            'attachments.*' => ['file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ];
    }
}