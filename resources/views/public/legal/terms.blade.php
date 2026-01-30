@extends('layouts.site')

{{-- SEO dos Termos --}}
@section('title', 'Termos de Uso')
@section('meta_description', 'Regras e condições para utilização dos serviços da Suporte TI.')

@section('content')
<div class="relative py-20 min-h-screen">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] left-[10%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid lg:grid-cols-4 gap-12">
            
            {{-- SIDEBAR --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="sticky top-24">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Jurídico</h3>
                    <nav class="space-y-1">
                        <a href="{{ route('terms') }}" class="block px-4 py-2 rounded-xl text-sm font-bold text-white bg-white/10 border border-white/5 transition">
                            Termos de Uso
                        </a>
                        <a href="{{ route('privacy') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            Política de Privacidade
                        </a>
                        <a href="{{ route('sla') }}" class="block px-4 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition">
                            SLA (Níveis de Serviço)
                        </a>
                    </nav>
                </div>
            </div>

            {{-- CONTEÚDO --}}
            <div class="lg:col-span-3">
                <div class="mb-8">
                    <h1 class="text-4xl font-bold text-white mb-2">Termos de Uso</h1>
                    <p class="text-slate-400">Regras para utilização dos nossos serviços.</p>
                </div>

                <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-10 space-y-10 text-slate-300 leading-relaxed">
                    
                    <section>
                        <h3 class="text-xl font-bold text-white mb-4">1. Aceitação</h3>
                        <p>Ao acessar a plataforma <strong>Suporte TI</strong>, você concorda integralmente com estes termos. Se não concordar com alguma parte, não deverá utilizar nossos serviços.</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4">2. Uso da Licença</h3>
                        <p>É concedida permissão temporária para acessar o painel de suporte. Sob esta licença, você não pode:</p>
                        <ul class="list-disc pl-5 mt-3 space-y-1 text-slate-400">
                            <li>Tentar descompilar ou fazer engenharia reversa de qualquer software contido no site;</li>
                            <li>Remover quaisquer direitos autorais ou outras notações de propriedade;</li>
                            <li>Transferir suas credenciais de acesso para outra pessoa ("compartilhamento de conta").</li>
                        </ul>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4">3. Cancelamento e Suspensão</h3>
                        <p>Podemos encerrar ou suspender seu acesso imediatamente, sem aviso prévio ou responsabilidade, por qualquer motivo, inclusive se você violar estes Termos. Após a rescisão, seu direito de usar o Serviço cessará imediatamente.</p>
                    </section>

                    <section>
                        <h3 class="text-xl font-bold text-white mb-4">4. Limitação de Responsabilidade</h3>
                        <p>Em nenhum caso o Suporte TI será responsável por danos indiretos, incidentais ou consequentes decorrentes do uso ou incapacidade de usar o serviço.</p>
                    </section>

                    <section class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20">
                        <h3 class="text-lg font-bold text-indigo-300 mb-2">5. Foro e Legislação</h3>
                        <p class="text-sm">Estes termos são regidos e interpretados de acordo com as leis do <strong>Brasil</strong>. Você se submete irrevogavelmente à jurisdição exclusiva dos tribunais do estado do Rio de Janeiro.</p>
                    </section>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection