@extends('layouts.site')

{{-- SEO da Privacidade --}}
@section('title', 'Política de Privacidade - Proteção de Dados e LGPD')
@section('meta_description', 'Como coletamos, usamos e protegemos os seus dados pessoais. Compromisso com a segurança da informação e conformidade com a LGPD.')

@section('content')
{{-- ✅ WRAPPER ALPINE --}}
<div class="relative py-20 min-h-screen print:bg-white print:text-black" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 300)">
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none print:hidden">
        <div class="absolute top-[10%] right-[10%] w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6 print:hidden">
                {{-- Skeleton Sidebar --}}
                <div x-show="!loaded" class="sticky top-24 space-y-6 animate-pulse">
                    <div class="h-6 w-32 bg-white/5 rounded"></div>
                    <div class="h-4 w-20 bg-white/5 rounded"></div>
                    <div class="space-y-2">
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                        <div class="h-10 w-full bg-white/5 rounded-xl"></div>
                    </div>
                </div>

                {{-- Real Sidebar --}}
                <div x-show="loaded" style="display: none;" class="sticky top-24">
                    {{-- Botão Voltar --}}
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
                        <a href="{{ route('sla') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition {{ request()->routeIs('sla') ? 'bg-white/10 text-white border border-white/5 shadow-lg shadow-black/5' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>

                    <div class="mt-8 p-4 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-cyan-500/10 border border-white/10">
                        <p class="text-xs text-slate-400 mb-2">Dúvidas sobre dados?</p>
                        <a href="mailto:dpo@seudominio.com" class="flex items-center gap-2 text-sm font-bold text-cyan-400 hover:text-cyan-300 transition">
                            <span>Fale com nosso DPO</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                {{-- Skeleton Conteúdo --}}
                <div x-show="!loaded" class="animate-pulse space-y-8">
                    <div class="h-10 w-3/4 bg-white/5 rounded-xl mb-8"></div>
                    <div class="space-y-4">
                        <div class="h-6 w-1/3 bg-white/5 rounded mb-4"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-2/3 bg-white/5 rounded"></div>
                    </div>
                    <div class="space-y-4">
                        <div class="h-6 w-1/3 bg-white/5 rounded mb-4"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                    </div>
                </div>

                <div x-show="loaded" style="display: none;" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="mb-8 border-b border-white/10 pb-8 print:border-black/10">
                        <h1 class="text-4xl font-bold text-white mb-2 print:text-black">Política de Privacidade</h1>
                        <p class="text-slate-400 print:text-gray-600">Última atualização: {{ date('d/m/Y') }}</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-10 space-y-10 text-slate-300 leading-relaxed print:bg-transparent print:border-none print:p-0 print:text-black">
                        
                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2 print:text-black">
                                <span class="text-cyan-400 print:text-black">1.</span> Coleta de Dados
                            </h3>
                            <p class="print:text-gray-800">Solicitamos informações pessoais apenas quando realmente precisamos delas para lhe fornecer um serviço. A coleta é feita por meios justos e legais, com o seu conhecimento e consentimento. Os dados que coletamos incluem:</p>
                            <ul class="list-disc pl-5 mt-3 space-y-1 text-slate-400 print:text-gray-800 marker:text-cyan-500">
                                <li>Nome completo e E-mail para identificação;</li>
                                <li>Dados de IP e navegador para segurança do sistema;</li>
                                <li>Informações fornecidas na abertura de chamados.</li>
                            </ul>
                        </section>

                        {{-- ... demais seções ... --}}
                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2 print:text-black">
                                <span class="text-cyan-400 print:text-black">2.</span> Cookies e Rastreamento
                            </h3>
                            <p class="print:text-gray-800">Utilizamos cookies essenciais para manter a sua sessão segura e lembrar as suas preferências. Não utilizamos cookies de terceiros para fins publicitários sem o seu consentimento explícito.</p>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2 print:text-black">
                                <span class="text-cyan-400 print:text-black">3.</span> Seus Direitos (LGPD)
                            </h3>
                            <p class="mb-3 print:text-gray-800">Você tem total controle sobre os seus dados. De acordo com a Lei Geral de Proteção de Dados, você tem o direito de:</p>
                            <div class="grid sm:grid-cols-2 gap-4 print:block print:space-y-4">
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5 print:border-gray-300 print:bg-gray-50">
                                    <div class="font-bold text-white mb-1 print:text-black">Acesso e Correção</div>
                                    <div class="text-xs text-slate-400 print:text-gray-600">Solicitar cópia ou corrigir dados incompletos.</div>
                                </div>
                                <div class="p-4 rounded-xl bg-white/5 border border-white/5 print:border-gray-300 print:bg-gray-50">
                                    <div class="font-bold text-white mb-1 print:text-black">Exclusão</div>
                                    <div class="text-xs text-slate-400 print:text-gray-600">Solicitar a remoção dos seus dados de nossos sistemas.</div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2 print:text-black">
                                <span class="text-cyan-400 print:text-black">4.</span> Segurança
                            </h3>
                            <p class="print:text-gray-800">Armazenamos seus dados em servidores seguros com criptografia SSL e acesso restrito. Apenas colaboradores autorizados têm acesso às informações estritamente necessárias para o suporte.</p>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection