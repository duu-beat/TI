@extends('layouts.site')

@section('content')
<div class="relative py-20 min-h-screen">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[30%] left-[30%] w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="sticky top-24">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Jurídico</h3>
                    <nav class="space-y-1">
                        <a href="{{ route('terms') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            Termos de Uso
                        </a>
                        <a href="{{ route('privacy') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            Política de Privacidade
                        </a>
                        <a href="{{ route('sla') }}" class="block px-4 py-2 rounded-xl text-sm font-bold text-white bg-white/10 border border-white/5 transition">
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2">SLA e Prazos</h1>
                    <p class="text-slate-400">Nossos compromissos de tempo e qualidade.</p>
                </div>

                {{-- Tabela de Prioridades --}}
                <div class="grid md:grid-cols-3 gap-6 mb-12">
                    
                    {{-- Baixa --}}
                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-6 relative overflow-hidden group hover:border-cyan-500/30 transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-cyan-500"></div>
                            <h3 class="font-bold text-white">Prioridade Baixa</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6 h-10">Dúvidas gerais, configurações simples ou sugestões.</p>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white">Até 8 horas</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Resolução Estimada</div>
                                <div class="text-lg font-bold text-slate-300">Até 48 horas</div>
                            </div>
                        </div>
                    </div>

                    {{-- Média --}}
                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-6 relative overflow-hidden group hover:border-yellow-500/30 transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <h3 class="font-bold text-white">Prioridade Média</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6 h-10">Falhas parciais que não impedem o funcionamento total.</p>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white">Até 4 horas</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Resolução Estimada</div>
                                <div class="text-lg font-bold text-slate-300">Até 24 horas</div>
                            </div>
                        </div>
                    </div>

                    {{-- Alta --}}
                    <div class="rounded-3xl border border-red-500/30 bg-red-500/5 p-6 relative overflow-hidden group hover:bg-red-500/10 transition">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse"></div>
                            <h3 class="font-bold text-white">Prioridade Alta</h3>
                        </div>
                        <p class="text-xs text-red-200/70 mb-6 h-10">Sistema inoperante, servidor offline ou risco de dados.</p>
                        
                        <div class="space-y-3">
                            <div>
                                <div class="text-[10px] uppercase text-red-300/50 font-bold">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white">Imediata (Máx 1h)</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-red-300/50 font-bold">Resolução Estimada</div>
                                <div class="text-lg font-bold text-white">Prioridade Total</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-8">
                    <h3 class="text-xl font-bold text-white mb-4">Detalhes Operacionais</h3>
                    <ul class="space-y-4 text-slate-400 text-sm">
                        <li class="flex gap-3">
                            <span class="text-emerald-400 font-bold">⏰ Horário Comercial:</span>
                            <span>Segunda a Sexta, das 09:00 às 18:00 (Exceto feriados).</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="text-red-400 font-bold">🚨 Plantão:</span>
                            <span>Disponível 24/7 apenas para incidentes de <strong>Prioridade Alta</strong> via telefone de emergência.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection