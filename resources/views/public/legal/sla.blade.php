@extends('layouts.site')

{{-- SEO do SLA --}}
@section('title', 'SLA e Prazos de Atendimento')
@section('meta_description', 'Entenda nossos níveis de serviço (SLA), prazos de resposta e prioridades de atendimento para suporte técnico.')

@section('content')
<div class="relative py-20 min-h-screen print:bg-white print:text-black">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none print:hidden">
        <div class="absolute top-[30%] left-[30%] w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR (Código normalizado) --}}
            <div class="lg:col-span-1 space-y-6 print:hidden">
                <div class="sticky top-24">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6 transition group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="text-sm font-medium">Voltar ao Início</span>
                    </a>

                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Jurídico</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('terms') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('terms') ? 'bg-white/10 text-white border border-white/5 shadow-lg shadow-black/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Termos de Uso
                        </a>
                        <a href="{{ route('privacy') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('privacy') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 shadow-lg shadow-cyan-500/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Política de Privacidade
                        </a>
                        <a href="{{ route('sla') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('sla') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-lg shadow-emerald-500/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                <div class="mb-8 border-b border-white/10 pb-8 print:border-black/10">
                    <h1 class="text-4xl font-bold text-white mb-2 print:text-black">SLA e Prazos</h1>
                    <p class="text-slate-400 print:text-gray-600">Nossos compromissos de tempo e qualidade.</p>
                </div>

                {{-- Tabela de Prioridades --}}
                <div class="grid md:grid-cols-3 gap-6 mb-12 print:block print:space-y-6">
                    
                    {{-- Baixa --}}
                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-6 relative overflow-hidden group hover:border-cyan-500/30 transition print:bg-white print:border-gray-300 print:break-inside-avoid">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-cyan-500 print:bg-gray-400"></div>
                            <h3 class="font-bold text-white print:text-black">Prioridade Baixa</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6 h-10 print:text-gray-600 print:h-auto">Dúvidas gerais, configurações simples ou sugestões.</p>
                        
                        <div class="space-y-3 print:border-t print:border-gray-100 print:pt-3">
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white print:text-black">Até 8 horas</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Resolução Estimada</div>
                                <div class="text-lg font-bold text-slate-300 print:text-black">Até 48 horas</div>
                            </div>
                        </div>
                    </div>

                    {{-- Média --}}
                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-6 relative overflow-hidden group hover:border-yellow-500/30 transition print:bg-white print:border-gray-300 print:break-inside-avoid">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-yellow-500 print:bg-gray-600"></div>
                            <h3 class="font-bold text-white print:text-black">Prioridade Média</h3>
                        </div>
                        <p class="text-xs text-slate-400 mb-6 h-10 print:text-gray-600 print:h-auto">Falhas parciais que não impedem o funcionamento total.</p>
                        
                        <div class="space-y-3 print:border-t print:border-gray-100 print:pt-3">
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white print:text-black">Até 4 horas</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-slate-500 font-bold">Resolução Estimada</div>
                                <div class="text-lg font-bold text-slate-300 print:text-black">Até 24 horas</div>
                            </div>
                        </div>
                    </div>

                    {{-- Alta --}}
                    <div class="rounded-3xl border border-red-500/30 bg-red-500/5 p-6 relative overflow-hidden group hover:bg-red-500/10 transition print:bg-white print:border-black print:break-inside-avoid">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500 animate-pulse print:animate-none print:bg-black"></div>
                            <h3 class="font-bold text-white print:text-black">Prioridade Alta</h3>
                        </div>
                        <p class="text-xs text-red-200/70 mb-6 h-10 print:text-gray-800 print:h-auto">Sistema inoperante, servidor offline ou risco de dados.</p>
                        
                        <div class="space-y-3 print:border-t print:border-gray-200 print:pt-3">
                            <div>
                                <div class="text-[10px] uppercase text-red-300/50 font-bold print:text-gray-500">Primeira Resposta</div>
                                <div class="text-lg font-bold text-white print:text-black">Imediata (Máx 1h)</div>
                            </div>
                            <div>
                                <div class="text-[10px] uppercase text-red-300/50 font-bold print:text-gray-500">Resolução Estimada</div>
                                <div class="text-lg font-bold text-white print:text-black">Prioridade Total</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/50 p-8 print:bg-transparent print:border-gray-300">
                    <h3 class="text-xl font-bold text-white mb-4 print:text-black">Detalhes Operacionais</h3>
                    <ul class="space-y-4 text-slate-400 text-sm print:text-gray-800">
                        <li class="flex gap-3">
                            <span class="text-emerald-400 font-bold print:text-black">⏰ Horário Comercial:</span>
                            <span>Segunda a Sexta, das 09:00 às 18:00 (Exceto feriados).</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="text-red-400 font-bold print:text-black">🚨 Plantão:</span>
                            <span>Disponível 24/7 apenas para incidentes de <strong>Prioridade Alta</strong> via telefone de emergência.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection