<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::create([
            'question' => 'Como abro um novo chamado?',
            'answer' => 'Para abrir um chamado, acesse o Portal do Cliente, clique no botão "+ Novo Chamado" no canto superior direito e preencha os detalhes do seu problema. Pode anexar prints ou documentos.',
        ]);
        
        Faq::create([
            'question' => 'Qual o horário de atendimento?',
            'answer' => 'Nosso suporte funciona de Segunda a Sexta, das 09h às 18h. Chamados abertos fora deste horário serão priorizados no dia útil seguinte.',
        ]);

        Faq::create([
            'question' => 'Esqueci a minha senha. E agora?',
            'answer' => 'Na tela de login, clique em "Esqueceu a senha?". Você receberá um link no seu e-mail cadastrado para definir uma nova senha segura.',
        ]);

        Faq::create([
            'question' => 'Quanto tempo demora a resposta?',
            'answer' => 'Chamados de prioridade normal são respondidos em até 4 horas úteis. Chamados de alta prioridade (serviço parado) têm atenção imediata.',
        ]);
    }
}