@extends('layouts.site')

{{-- SEO da Privacidade --}}
@section('title', 'Política de Privacidade - Proteção de Dados e LGPD')
@section('meta_description', 'Como coletamos, usamos e protegemos seus dados pessoais. Compromisso com a segurança da informação e conformidade com a LGPD.')

@section('content')
<div class="relative py-20 min-h-screen">
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] right-[10%] w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        
        {{-- Layout com Sidebar --}}
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR DE NAVEGAÇÃO --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="sticky top-24">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Jurídico</h3>
                    <nav class="space-y-1">
                        <a href="{{ route('terms') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            Termos de Uso
                        </a>
                        <a href="{{ route('privacy') }}" class="block px-4 py-2 rounded-xl text-sm font-bold text-white bg-white/10 border border-white/5 transition">
                            Política de Privacidade
                        </a>
                        <a href="{{ route('sla') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>

                    <div class="mt-8 p-4 rounded-2xl bg-gradient-to-br from-indigo-500/10 to-cyan-500/10 border border-white/10">
                        <p class="text-xs text-slate-400 mb-2">Dúvidas sobre dados?</p>
                        <a href="mailto:dpo@seudominio.com" class="text-sm font-bold text-cyan-400 hover:underline">Fale com nosso DPO &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2">Política de Privacidade</h1>
                    <p class="text-slate-400">Última atualização: {{ date('d/m/Y') }}</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-10 space-y-10 text-slate-300 leading-relaxed">
                    
                    <section>
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="text-cyan-400">1.</span> Coleta de Dados
                        </h3>
                        <p>Solicitamos informações pessoais apenas quando realmente precisamos delas para lhe fornecer um serviço. A coleta é feita por meios justos e legais, com o seu conhecimento e consentimento. Os dados que coletamos incluem:</p>
                        <ul class="list-disc pl-5 mt-3 space-y-1 text-slate-400">
                            <li>Nome completo e E-mail para identificação;</li>
                            <li>Dados de IP e navegador para segurança do sistema;</li>
                            <li>Informações fornecidas na abertura de chamados.</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="text-cyan-400">2.</span> Cookies e Rastreamento
                        </h3>
                        <p>Utilizamos cookies essenciais para manter a sua sessão segura e lembrar as suas preferências. Não utilizamos cookies de terceiros para fins publicitários sem o seu consentimento explícito.</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="text-cyan-400">3.</span> Seus Direitos (LGPD)
                        </h3>
                        <p class="mb-3">Você tem total controle sobre os seus dados. De acordo com a Lei Geral de Proteção de Dados, você tem o direito de:</p>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <div class="font-bold text-white mb-1">Acesso e Correção</div>
                                <div class="text-xs text-slate-400">Solicitar cópia ou corrigir dados incompletos.</div>
                            </div>
                            <div class="p-4 rounded-xl bg-white/5 border border-white/5">
                                <div class="font-bold text-white mb-1">Exclusão</div>
                                <div class="text-xs text-slate-400">Solicitar a remoção dos seus dados de nossos sistemas.</div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="text-cyan-400">4.</span> Segurança
                        </h3>
                        <p>Armazenamos seus dados em servidores seguros com criptografia SSL e acesso restrito. Apenas colaboradores autorizados têm acesso às informações estritamente necessárias para o suporte.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection