<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketMessage; // Vamos salvar como uma "nota interna" ou rascunho
use App\Services\AiSupportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAiSuggestion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function handle(AiSupportService $aiService)
    {
        $suggestion = $aiService->generateSuggestedResponse($this->ticket);

        if ($suggestion) {
            // Opção A: Salvar como uma nota interna especial
            // Você precisaria adicionar um campo 'is_ai_suggestion' na tabela ticket_messages
            // Ou apenas criar uma nota interna com um prefixo.
            
            // Vamos assumir que você tem o sistema de notas internas do documento anterior:
            TicketMessage::create([
                'ticket_id' => $this->ticket->id,
                'user_id' => null, // null indica Sistema/Bot
                'message' => "🤖 **Sugestão da IA:**<br>" . $suggestion,
                'is_internal_note' => true, // Garante que o cliente não veja
            ]);
        }
    }
}