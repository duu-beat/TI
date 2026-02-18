@extends('layouts.site')

{{-- SEO dos Termos --}}
@section('title', 'Termos de Uso')
@section('meta_description', 'Regras e condições para utilização dos serviços da Suporte TI.')

@section('content')
{{-- ✅ WRAPPER ALPINE --}}
<div class="relative py-20 min-h-screen print:bg-white print:text-black" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none print:hidden">
        <div class="absolute top-[10%] left-[10%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6 print:hidden">
                {{-- Skeleton --}}
                <div x-show="!loaded" class="sticky top-24 space-y-6 animate-pulse">
                    <div class="h-6 w-32 bg-white/5 rounded"></div>
                    <div class="h-4 w-20 bg-white/5 rounded"></div>
                    <div class="space-y-2">
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                    </div>
                </div>

                {{-- Real --}}
                <div x-show="loaded" style="display: none;" class="sticky top-24">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6 transition group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="text-sm font-medium">Voltar ao Início</span>
                    </a>

                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Jurídico</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('terms') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('terms') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shadow-lg shadow-indigo-500/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Termos de Uso
                        </a>
                        <a href="{{ route('privacy') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('privacy') ? 'bg-white/10 text-white border border-white/5 shadow-lg shadow-black/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Política de Privacidade
                        </a>
                        <a href="{{ route('sla') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('sla') ? 'bg-white/10 text-white border border-white/5 shadow-lg shadow-black/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                {{-- Skeleton Conteúdo --}}
                <div x-show="!loaded" class="animate-pulse space-y-8">
                    <div class="h-10 w-3/4 bg-white/5 rounded-xl mb-8"></div>
                    @for($i=0; $i<4; $i++)
                    <div class="space-y-4">
                        <div class="h-6 w-1/3 bg-white/5 rounded mb-4"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-2/3 bg-white/5 rounded"></div>
                    </div>
                    @endfor
                </div>

                {{-- Real --}}
                <div x-show="loaded" style="display: none;" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="mb-8 border-b border-white/10 pb-8 print:border-black/10">
                        <h1 class="text-4xl font-bold text-white mb-2 print:text-black">Termos de Uso</h1>
                        <p class="text-slate-400 print:text-gray-600">Regras para utilização dos nossos serviços.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-10 space-y-10 text-slate-300 leading-relaxed print:bg-transparent print:border-none print:p-0 print:text-black">
                        
                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 print:text-black">1. Aceitação</h3>
                            <p class="print:text-gray-800">Ao acessar a plataforma <strong>Suporte TI</strong>, você concorda integralmente com estes termos. Se não concordar com alguma parte, não deverá utilizar nossos serviços.</p>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 print:text-black">2. Uso da Licença</h3>
                            <p class="print:text-gray-800">É concedida permissão temporária para acessar o painel de suporte. Sob esta licença, você não pode:</p>
                            <ul class="list-disc pl-5 mt-3 space-y-1 text-slate-400 marker:text-indigo-500 print:text-gray-800">
                                <li>Tentar descompilar ou fazer engenharia reversa de qualquer software contido no site;</li>
                                <li>Remover quaisquer direitos autorais ou outras notações de propriedade;</li>
                                <li>Transferir suas credenciais de acesso para outra pessoa ("compartilhamento de conta").</li>
                            </ul>
                        </section>

                        {{-- ... demais seções ... --}}
                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 print:text-black">3. Cancelamento e Suspensão</h3>
                            <p class="print:text-gray-800">Podemos encerrar ou suspender seu acesso imediatamente, sem aviso prévio ou responsabilidade, por qualquer motivo, inclusive se você violar estes Termos. Após a rescisão, seu direito de usar o Serviço cessará imediatamente.</p>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 print:text-black">4. Limitação de Responsabilidade</h3>
                            <p class="print:text-gray-800">Em nenhum caso o Suporte TI será responsável por danos indiretos, incidentais ou consequentes decorrentes do uso ou incapacidade de usar o serviço.</p>
                        </section>

                        <section class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 print:bg-gray-50 print:border-gray-300">
                            <h3 class="text-lg font-bold text-indigo-300 mb-2 print:text-black">5. Foro e Legislação</h3>
                            <p class="text-sm print:text-gray-700">Estes termos são regidos e interpretados de acordo com as leis do <strong>Brasil</strong>. Você se submete irrevogavelmente à jurisdição exclusiva dos tribunais do estado do Rio de Janeiro.</p>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection