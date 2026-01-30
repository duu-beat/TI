@extends('layouts.site')

{{-- SEO da Página Sobre --}}
@section('title', 'Sobre Nós - Suporte TI')
@section('meta_description', 'Conheça a Suporte TI em Seropédica. Transformamos a tecnologia de empresas com prevenção, monitoramento proativo e agilidade.')

@section('content')
<div class="relative py-24 min-h-screen overflow-hidden">
    
    {{-- Background Glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] left-[10%] w-[800px] h-[800px] bg-blue-900/20 rounded-full blur-[150px]"></div>
        <div class="absolute bottom-[20%] right-[10%] w-[600px] h-[600px] bg-indigo-900/20 rounded-full blur-[150px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        
        {{-- 1. HERO SECTION --}}
        <div class="text-center mb-32">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-8 hover:bg-blue-500/20 transition cursor-default">
                🏢 Nossa Essência
            </div>
            <h1 class="text-5xl md:text-7xl font-black text-white tracking-tight mb-8 leading-tight">
                Tecnologia invisível,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">resultados visíveis.</span>
            </h1>
            <p class="text-xl text-slate-400 max-w-3xl mx-auto leading-relaxed">
                Não somos apenas técnicos de informática. Somos arquitetos de estabilidade. 
                Acreditamos que a melhor tecnologia é aquela que funciona tão bem que você nem percebe que ela está lá.
            </p>
        </div>

        {{-- 2. MANIFESTO (TEXTO DENSO) --}}
        <div class="grid md:grid-cols-12 gap-12 items-start mb-32">
            <div class="md:col-span-5 relative">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-3xl rotate-3 opacity-20 blur-lg"></div>
                <div class="relative h-full min-h-[400px] rounded-3xl bg-slate-900 border border-white/10 p-8 flex flex-col justify-end overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40 mix-blend-overlay"></div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold text-white mb-2">O futuro é agora.</h3>
                        <p class="text-sm text-slate-300">E nós estamos construindo a infraestrutura para ele.</p>
                    </div>
                </div>
            </div>
            <div class="md:col-span-7 space-y-6 text-lg text-slate-300 leading-relaxed">
                <h2 class="text-3xl font-bold text-white mb-4">Por que fazemos o que fazemos?</h2>
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

        {{-- 3. NOSSA HISTÓRIA (TIMELINE EXPANDIDA) --}}
        <div class="mb-32">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-white mb-4">A Nossa Jornada</h2>
                <p class="text-slate-400">De uma pequena sala para a gestão de centenas de endpoints.</p>
            </div>

            <div class="space-y-12 relative border-l border-white/10 ml-4 md:ml-1/2 md:-translate-x-px">
                
                {{-- Marco 1 --}}
                <div class="relative flex flex-col md:flex-row items-center justify-between group">
                    <div class="absolute left-[-5px] md:left-1/2 md:-ml-[5px] w-[10px] h-[10px] rounded-full bg-indigo-500 ring-4 ring-slate-950 group-hover:scale-150 transition duration-500"></div>
                    
                    <div class="md:w-1/2 md:pr-12 md:text-right pl-8 md:pl-0 mb-4 md:mb-0">
                        <span class="text-indigo-400 font-bold text-sm tracking-widest uppercase">2018</span>
                        <h3 class="text-xl font-bold text-white mt-1 mb-2">A Fundação</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Tudo começou como um projeto pessoal de consultoria. O foco inicial era a manutenção de hardware e redes domésticas. A qualidade do serviço gerou um "boca a boca" poderoso na região.
                        </p>
                    </div>
                    <div class="md:w-1/2 md:pl-12 pl-8"></div>
                </div>

                {{-- Marco 2 --}}
                <div class="relative flex flex-col md:flex-row-reverse items-center justify-between group">
                    <div class="absolute left-[-5px] md:left-1/2 md:-ml-[5px] w-[10px] h-[10px] rounded-full bg-cyan-500 ring-4 ring-slate-950 group-hover:scale-150 transition duration-500"></div>
                    
                    <div class="md:w-1/2 md:pl-12 pl-8 mb-4 md:mb-0">
                        <span class="text-cyan-400 font-bold text-sm tracking-widest uppercase">2021</span>
                        <h3 class="text-xl font-bold text-white mt-1 mb-2">Pivô para B2B</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Percebemos que as empresas locais careciam de suporte profissional. Mudámos o foco 100% para o corporativo, implementando contratos de SLA e ferramentas de gestão remota (RMM).
                        </p>
                    </div>
                    <div class="md:w-1/2 md:pr-12 md:text-right"></div>
                </div>

                {{-- Marco 3 --}}
                <div class="relative flex flex-col md:flex-row items-center justify-between group">
                    <div class="absolute left-[-5px] md:left-1/2 md:-ml-[5px] w-[10px] h-[10px] rounded-full bg-emerald-500 ring-4 ring-slate-950 group-hover:scale-150 transition duration-500"></div>
                    
                    <div class="md:w-1/2 md:pr-12 md:text-right pl-8 md:pl-0 mb-4 md:mb-0">
                        <span class="text-emerald-400 font-bold text-sm tracking-widest uppercase">2024</span>
                        <h3 class="text-xl font-bold text-white mt-1 mb-2">Escala Digital</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Lançamento do nosso ecossistema digital próprio. Agora, os clientes têm controle total via portal, e a nossa equipa utiliza IA para prever falhas em servidores antes que elas aconteçam.
                        </p>
                    </div>
                    <div class="md:w-1/2 md:pl-12 pl-8"></div>
                </div>

            </div>
        </div>

        {{-- 4. METODOLOGIA (NOVO) --}}
        <div class="mb-32">
            <div class="p-10 rounded-[2.5rem] border border-white/10 bg-slate-900/50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-[100px] pointer-events-none"></div>
                
                <h2 class="text-3xl font-bold text-white mb-12 text-center">Como Trabalhamos</h2>
                
                <div class="grid md:grid-cols-3 gap-8 relative z-10">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center text-3xl mb-6 shadow-lg shadow-black/50">🔎</div>
                        <h3 class="text-lg font-bold text-white mb-3">1. Diagnóstico Profundo</h3>
                        <p class="text-slate-400 text-sm">Não tratamos apenas o sintoma. Investigamos a causa raiz do problema para garantir que ele não volte a acontecer.</p>
                    </div>
                    <div class="text-center relative">
                        {{-- Seta (apenas desktop) --}}
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
                <h2 class="text-3xl font-bold text-white">Nossos Pilares</h2>
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

        {{-- CTA FINAL --}}
        <div class="text-center border-t border-white/10 pt-20">
            <h2 class="text-3xl font-bold text-white mb-6">A sua empresa merece este nível de suporte.</h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('contact') }}" class="px-8 py-3 rounded-xl bg-white text-slate-900 font-bold hover:bg-slate-200 transition">
                    Agendar Reunião
                </a>
                <a href="{{ route('portfolio') }}" class="px-8 py-3 rounded-xl text-slate-300 font-medium hover:text-white transition">
                    Ver Cases de Sucesso &rarr;
                </a>
            </div>
        </div>

    </div>
</div>
@endsection