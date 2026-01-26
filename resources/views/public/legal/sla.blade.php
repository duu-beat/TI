@extends('layouts.site')

@section('content')
<div class="relative py-20">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[30%] left-[30%] w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        <div class="mb-12 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest mb-4">
                Compromisso de Qualidade
            </div>
            <h1 class="text-4xl font-bold text-white mb-4">SLA (Acordo de Nível de Serviço)</h1>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Nosso compromisso com a agilidade e a transparência. Entenda nossos prazos de atendimento e resolução.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-12">
            {{-- Card Baixa --}}
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 backdrop-blur-md">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <h3 class="font-bold text-white">Prioridade Baixa</h3>
                </div>
                <p class="text-sm text-slate-400 mb-4">Dúvidas gerais, configurações simples ou solicitações que não impedem o trabalho.</p>
                <div class="text-2xl font-bold text-white">Até 24h</div>
                <div class="text-xs text-slate-500 uppercase font-bold mt-1">Para resposta inicial</div>
            </div>

            {{-- Card Média --}}
            <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-6 backdrop-blur-md relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-16 bg-yellow-500/10 rounded-bl-full"></div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <h3 class="font-bold text-white">Prioridade Média</h3>
                </div>
                <p class="text-sm text-slate-400 mb-4">Falhas parciais, lentidão no sistema ou problemas que dificultam o trabalho.</p>
                <div class="text-2xl font-bold text-white">Até 4h</div>
                <div class="text-xs text-slate-500 uppercase font-bold mt-1">Para resposta inicial</div>
            </div>

            {{-- Card Alta --}}
            <div class="rounded-2xl border border-red-500/30 bg-slate-900/60 p-6 backdrop-blur-md relative overflow-hidden">
                <div class="absolute inset-0 bg-red-500/5"></div>
                <div class="flex items-center gap-3 mb-4 relative z-10">
                    <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                    <h3 class="font-bold text-white">Prioridade Alta</h3>
                </div>
                <p class="text-sm text-slate-400 mb-4 relative z-10">Sistema parado, servidor offline ou falha crítica de segurança.</p>
                <div class="text-2xl font-bold text-white relative z-10">Imediato</div>
                <div class="text-xs text-slate-500 uppercase font-bold mt-1 relative z-10">Máximo 1 hora</div>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-8">
            <h3 class="text-xl font-bold text-white mb-4">Disponibilidade e Horários</h3>
            <div class="space-y-4 text-slate-400 text-sm">
                <p>
                    <strong class="text-white">Horário Comercial:</strong> Segunda a Sexta, das 09:00 às 18:00.
                </p>
                <p>
                    <strong class="text-white">Plantão:</strong> Para clientes com contrato "Premium", oferecemos suporte fora do horário comercial apenas para incidentes de Prioridade Alta.
                </p>
                <p>
                    <strong class="text-white">Manutenção Programada:</strong> Serão comunicadas com 48 horas de antecedência e realizadas preferencialmente fora do horário comercial.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection