<?php

namespace App\Services;

use OpenAI;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class AiSupportService
{
    protected $client;

    public function __construct()
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
        $this->client = OpenAI::client($apiKey);
    }

    public function generateSuggestedResponse(Ticket $ticket): ?string
    {
        try {
            $prompt = "Você é um suporte técnico de TI nível sênior.
            Analise o seguinte chamado e forneça uma sugestão de resposta técnica, empática e direta para o cliente.
            
            Assunto: {$ticket->subject}
            Categoria: {$ticket->category}
            Descrição: {$ticket->message}
            
            Responda em formato HTML simples (apenas <p>, <ul>, <li>, <strong>).";

            $result = $this->client->chat()->create([
                'model' => 'gpt-4o-mini', // Ou gpt-3.5-turbo para economizar
                'messages' => [
                    ['role' => 'system', 'content' => 'Você é um assistente útil de suporte técnico.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'max_tokens' => 500,
            ]);

            return $result->choices[0]->message->content;

        } catch (\Exception $e) {
            Log::error("Erro na OpenAI: " . $e->getMessage());
            return null;
        }
    }
}