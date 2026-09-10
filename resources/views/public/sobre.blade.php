@extends('layouts.site')

{{-- SEO da Página Sobre --}}
@section('title', 'Sobre Nós - Suporte TI')
@section('meta_description', 'Conheça a Suporte TI em Seropédica. Transformamos a tecnologia de empresas com prevenção, monitoramento proativo e agilidade.')

@section('content')
{{-- ✅ WRAPPER ALPINE ADICIONADO --}}
<div class="relative py-24 min-h-screen overflow-hidden" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
    
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] left-[10%] w-[800px] h-[800px] bg-blue-900/20 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[600px] h-[600px] bg-indigo-900/20 rounded-full blur-[150px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        
        {{-- 💀 SKELETON LOADER --}}
        <div x-show="!loaded" class="animate-pulse space-y-32">
            <div class="text-center space-y-6">
                <div class="h-8 w-40 bg-white/5 rounded-full mx-auto"></div>
                <div class="h-16 w-3/4 bg-white/5 rounded-2xl mx-auto"></div>
                <div class="h-4 w-1/2 bg-white/5 rounded mx-auto"></div>
            </div>
            
            <div class="grid md:grid-cols-12 gap-12">
                <div class="md:col-span-5 h-[400px] bg-white/5 rounded-3xl"></div>
                <div class="md:col-span-7 space-y-6">
                    <div class="h-10 w-3/4 bg-white/5 rounded-xl"></div>
                    <div class="space-y-3">
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-full bg-white/5 rounded"></div>
                        <div class="h-4 w-2/3 bg-white/5 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ CONTEÚDO REAL --}}
        <div x-show="loaded" style="display: none;"
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0">
            
            {{-- 1. HERO SECTION --}}
            <div class="text-center mb-32">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-8 hover:bg-blue-500/20 transition cursor-default">
                    🏢 {{ __('messages.public.about_badge') }}
                </div>
                <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8 leading-tight">
                    {{ __('messages.public.about_title') }}<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">{{ __('messages.public.about_title_highlight') }}</span>
                </h1>
                <p class="text-xl text-slate-400 max-w-3xl mx-auto leading-relaxed">
                    {{ __('messages.public.about_description') }}
                </p>
            </div>

            {{-- 2. MANIFESTO --}}
            <div class="grid md:grid-cols-12 gap-12 items-start mb-32">
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-3xl rotate-3 opacity-20 blur-lg"></div>
                    <div class="relative h-full min-h-[400px] rounded-3xl bg-slate-900 border border-white/10 p-8 flex flex-col justify-end overflow-hidden">
                        {{-- Imagem de Fundo --}}
                        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069&auto=format&fit=crop')] bg-cover bg-center opacity-40 mix-blend-overlay"></div>
                        <div class="relative z-10">
                            <h3 class="text-2xl font-bold text-white mb-2">{{ __('messages.public.about_future') }}</h3>
                            <p class="text-sm text-slate-300">{{ __('messages.public.about_future_text') }}</p>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-7 space-y-6 text-lg text-slate-300 leading-relaxed">
                    <h2 class="text-3xl font-bold text-white mb-4">{{ __('messages.public.about_why_title') }}</h2>
                    <p>
                        No mundo moderno, um minuto offline pode custar milhares de reais. Uma falha de segurança pode destruir uma reputação construída em décadas. A tecnologia deixou de ser um acessório para se tornar o coração pulsante de qualquer negócio.
                    </p>
                    <p>
                        Fundada em Seropédica, a <strong>Suporte TI</strong> nasceu da observação de uma lacuna crítica no mercado: a falta de atendimento preventivo. A maioria das empresas de suporte lucra com o caos, consertando o que quebra. Nós decidimos seguir o caminho oposto.
                    </p>
                    <p>
                        O nosso modelo de negócio é baseado na <strong>prevenção</strong>. Investimos em monitoramento proativo, automação e processos robustos para garantir que os problemas sejam resolvidos antes mesmo de o cliente pegar o telefone.
                    </p>
                    <div class="pt-4 border-t border-white/10 mt-6">
                        <p class="text-indigo-400 font-medium italic">
                            "O nosso maior sucesso é quando o cliente passa meses sem precisar abrir um chamado, porque tudo simplesmente funciona."
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. NOSSA TRAJETÓRIA --}}
            <div class="mb-32 relative">
                <div class="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-blue-500/0 via-blue-500/50 to-blue-500/0 hidden md:block"></div>
                
                <div class="md:pl-12">
                    <h2 class="text-3xl font-bold text-white mb-8">{{ __('messages.public.about_history_title') }}</h2>
                    
                    <div class="space-y-8 text-lg text-slate-400 leading-relaxed text-justify">
                        <p>
                            <span class="text-white font-bold">2018: O Início.</span> Tudo começou de forma modesta, mas ambiciosa, aqui mesmo em Seropédica. Nascemos como um projeto de consultoria técnica focado na qualidade extrema. Naquela época, o nosso marketing era puramente o resultado do nosso trabalho: redes que não caíam e computadores que não travavam. O "boca a boca" foi a nossa primeira alavanca de crescimento, provando que havia espaço para quem trabalhasse com seriedade.
                        </p>
                        
                        <p>
                            <span class="text-white font-bold">2021: A Profissionalização (B2B).</span> Ao observar o mercado local, percebemos uma falha grave: as empresas estavam desassistidas. O "sobrinho que conserta computador" não era mais suficiente para negócios que dependiam de dados e velocidade. Foi nesse ano que mudámos o nosso foco 100% para o mercado corporativo. Implementamos contratos de SLA (Níveis de Serviço), ferramentas de Gestão Remota (RMM) e trouxemos processos de multinacionais para o comércio local.
                        </p>

                        <p>
                            <span class="text-white font-bold">2024 e Além: A Era Digital.</span> Hoje, não somos apenas uma empresa de manutenção; somos uma plataforma de inteligência. Com o lançamento do nosso ecossistema digital próprio, os nossos clientes têm transparência total sobre seus ativos. Utilizamos Inteligência Artificial para prever falhas em servidores e automação para corrigir problemas silenciosamente. Crescemos em estrutura, equipa e tecnologia, mas a nossa missão permanece inalterada: garantir a sua paz de espírito tecnológica.
                        </p>
                    </div>
                </div>
            </div>

            {{-- 4. METODOLOGIA --}}
            <div class="mb-32">
                <div class="p-10 rounded-[2.5rem] border border-white/10 bg-slate-900/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
                    
                    <h2 class="text-3xl font-bold text-white mb-12 text-center">{{ __('messages.public.about_method_title') }}</h2>
                    
                    <div class="grid md:grid-cols-3 gap-8 relative z-10">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-3xl mb-6 shadow-lg shadow-black/50">🔎</div>
                            <h3 class="text-lg font-bold text-white mb-3">1. Diagnóstico Profundo</h3>
                            <p class="text-slate-400 text-sm">Não tratamos apenas o sintoma. Investigamos a causa raiz do problema para garantir que ele não volte a acontecer.</p>
                        </div>
                        <div class="text-center relative">
                            {{-- Seta --}}
                            <div class="hidden md:block absolute top-8 -right-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                            
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-indigo-600 border border-indigo-400/30 flex items-center justify-center text-3xl mb-6 shadow-lg shadow-indigo-500/20">⚙️</div>
                            <h3 class="text-lg font-bold text-white mb-3">2. Ação Cirúrgica</h3>
                            <p class="text-slate-400 text-sm">Execução rápida e precisa, minimizando o impacto na operação da sua empresa. Utilizamos as melhores práticas do mercado.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-3xl mb-6 shadow-lg shadow-black/50">🛡️</div>
                            <h3 class="text-lg font-bold text-white mb-3">3. Blindagem</h3>
                            <p class="text-slate-400 text-sm">Após a resolução, implementamos medidas preventivas e monitoramento para blindar o ambiente contra novas falhas.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. VALORES --}}
            <div class="mb-24">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-white">{{ __('messages.public.about_values_title') }}</h2>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/30 hover:bg-white/5 transition duration-300">
                        <div class="text-indigo-400 font-bold mb-4 text-sm uppercase tracking-wider">01. Agilidade</div>
                        <p class="text-slate-300 text-sm leading-relaxed">Tempo é o ativo mais valioso. Respeitamos o seu SLA com rigor militar.</p>
                    </div>
                    <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/30 hover:bg-white/5 transition duration-300">
                        <div class="text-cyan-400 font-bold mb-4 text-sm uppercase tracking-wider">02. Transparência</div>
                        <p class="text-slate-300 text-sm leading-relaxed">Sem letras miúdas. Você sabe exatamente o que foi feito e porquê.</p>
                    </div>
                    <div class="p-8 rounded-3xl border border-white/10 bg-slate-900/30 hover:bg-white/5 transition duration-300">
                        <div class="text-emerald-400 font-bold mb-4 text-sm uppercase tracking-wider">03. Excelência</div>
                        <p class="text-slate-300 text-sm leading-relaxed">Não aceitamos "gambiarra". Entregamos soluções definitivas e documentadas.</p>
                    </div>
                </div>
            </div>

            {{-- CTA FINAL COM LÓGICA PERSONALIZADA --}}
            @unless(auth()->check() && auth()->user()->isAdmin())
                <div class="text-center border-t border-white/10 pt-20">
                    <h2 class="text-3xl font-bold text-white mb-6">{{ __('messages.public.about_cta_title') }}</h2>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        
                        @guest
                            <a href="{{ route('contact') }}" class="px-8 py-3 rounded-xl bg-white text-slate-900 font-bold hover:bg-slate-200 transition">
                                {{ __('messages.public.schedule_meeting') }}
                            </a>
                        @endguest

                        <a href="{{ route('portfolio') }}" class="px-8 py-3 rounded-xl text-slate-300 font-medium hover:text-white transition">
                            {{ __('messages.public.success_cases') }} &rarr;
                        </a>

                    </div>
                </div>
            @endunless

        </div>
    </div>
</div>
@endsection