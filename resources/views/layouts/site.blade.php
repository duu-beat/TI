<!DOCTYPE html>
{{-- Declara o tipo do documento como HTML5 --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
{{-- Define o idioma da página dinamicamente pelo locale do Laravel (ex: pt-BR) e força modo dark via class "dark" --}}
<head>
    <meta charset="UTF-8">
    {{-- Define codificação UTF-8 para suportar acentos e caracteres especiais --}}

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Ajusta a página para ficar responsiva em dispositivos móveis --}}

    <title>{{ config('app.name', 'Suporte TI') }}</title>
    {{-- Título da aba do navegador. Pega o nome do app do config/app.php, senão usa "Suporte TI" --}}
    
    {{-- 1. FONTE OUTFIT --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    {{-- Otimização: abre conexão antecipada com o domínio do Google Fonts --}}
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Otimização: preconecta com o servidor de fontes (gstatic) com crossorigin --}}
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    {{-- Importa a fonte "Outfit" com vários pesos (300 a 700) --}}

    <style>
        body { font-family: 'Outfit', sans-serif; }
        /* Aplica a fonte Outfit em todo o site */
        
        /* 2. SCROLLBAR DARK */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        /* Define largura/altura da scrollbar (Chrome/Edge/Safari) */

        ::-webkit-scrollbar-track { background: #0f172a; }
        /* Cor do "trilho" da scrollbar (fundo) */

        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        /* Cor do "thumb" (barra que arrasta) e arredondamento */

        ::-webkit-scrollbar-thumb:hover { background: #475569; }
        /* Cor do thumb ao passar o mouse */
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Carrega assets do Vite: seu CSS e JS compilados (Tailwind, Alpine, etc.) --}}
</head>

<body class="bg-slate-950 text-slate-100 antialiased relative selection:bg-cyan-500 selection:text-white flex flex-col min-h-screen">
{{-- 
    Classes Tailwind no body:
    - bg-slate-950: fundo bem escuro
    - text-slate-100: texto claro
    - antialiased: suavização de fonte
    - relative: permite posicionar elementos absolutos dentro
    - selection:*: muda a cor quando seleciona texto
    - flex flex-col min-h-screen: layout em coluna com altura mínima da tela toda
--}}

    {{-- 3. MESH GRADIENT --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        {{-- Camada fixa no fundo (tela inteira), não clicável, escondendo overflow para os "glows" não vazarem --}}
        <div class="absolute top-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-600/10 blur-[120px]"></div>
        {{-- Bolha de brilho roxo no topo-direita com blur grande --}}
        <div class="absolute bottom-[0%] left-[-10%] w-[40%] h-[40%] rounded-full bg-cyan-600/10 blur-[120px]"></div>
        {{-- Bolha de brilho cyan no canto inferior-esquerdo com blur grande --}}
    </div>

    {{-- Conteúdo (Relative z-10) --}}
    <div class="relative z-10 flex flex-col flex-1" x-data="{ mobileMenuOpen: false }">
        {{-- 
            Camada principal acima do fundo (z-10)
            x-data do Alpine.js cria estado local:
            mobileMenuOpen controla abrir/fechar menu mobile
        --}}
        
        {{-- HEADER --}}
        <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl transition-all duration-300">
            {{-- 
                Header fixo no topo ao rolar (sticky)
                z-50 para ficar acima de tudo
                fundo semi-transparente + blur (efeito vidro)
                borda inferior sutil
            --}}
            <div class="max-w-7xl mx-auto px-6 py-4">
                {{-- Container central com largura máxima e padding --}}
                <div class="flex items-center justify-between gap-6">
                    {{-- Linha com logo à esquerda e navegação/btn à direita --}}
                    
                    {{-- LOGO --}}
                    <a href="{{ route('home') }}" class="flex items-center gap-1 group relative z-50">
                        {{-- 
                            Link que leva para a rota "home"
                            group permite usar group-hover nos elementos internos
                            z-50 evita que o blur/elementos por trás atrapalhem cliques
                        --}}
    
                        {{-- Bloco da Imagem --}}
                        <div class="relative">
                            {{-- container relativo para posicionar o glow por trás --}}
                            <div class="absolute inset-0 bg-cyan-500/20 blur-lg rounded-full opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            {{-- glow por trás do logo aparece no hover (opacity 0 -> 100) --}}
                            <img src="{{ asset('images/logosuporteTI.png') }}" alt="Suporte TI"
                                 class="h-16 md:h-20 w-auto object-contain group-hover:scale-105 transition" />
                            {{-- 
                                Imagem do logo (Laravel asset)
                                responsiva: 16 no mobile, 20 no md+
                                object-contain mantém proporção
                                cresce levemente no hover
                            --}}
                        </div>

                        {{-- Bloco do Texto --}}
                        <div class="leading-tight text-left">
                            {{-- agrupa o nome e subtítulo com espaçamento compacto --}}
                            <div class="font-bold tracking-tight text-white leading-none text-lg group-hover:text-cyan-400 transition">
                                Suporte TI
                            </div>
                            {{-- nome principal, troca cor no hover --}}
                            <div class="text-[10px] uppercase tracking-wider text-slate-400 font-medium">
                                Soluções Rápidas
                            </div>
                            {{-- subtítulo pequeno em caixa alta --}}
                        </div>
                    </a>

                    {{-- DESKTOP NAV --}}
                    <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
                        {{-- 
                            Nav só aparece no desktop (md:flex, hidden no mobile)
                            links com espaçamento e estilo
                        --}}
                        {{-- 🔥 Link SOBRE adicionado aqui --}}
                        <a class="hover:text-white transition hover:-translate-y-0.5 {{ request()->routeIs('sobre') ? 'text-cyan-400' : '' }}" href="{{ route('sobre') }}">Sobre</a>
                        {{-- 
                            Link "Sobre"
                            routeIs('sobre') pinta o link como ativo (cyan)
                            hover sobe levemente e fica branco
                        --}}
                        <a class="hover:text-white transition hover:-translate-y-0.5 {{ request()->routeIs('services') ? 'text-cyan-400' : '' }}" href="{{ route('services') }}">Serviços</a>
                        {{-- Link Serviços com destaque se rota atual for services --}}
                        <a class="hover:text-white transition hover:-translate-y-0.5 {{ request()->routeIs('faq') ? 'text-cyan-400' : '' }}" href="{{ route('faq') }}">FAQ</a>
                        {{-- Link FAQ com destaque se rota atual for faq --}}
                        <a class="hover:text-white transition hover:-translate-y-0.5 {{ request()->routeIs('portfolio') ? 'text-cyan-400' : '' }}" href="{{ route('portfolio') }}">Portfólio</a>
                        {{-- Link Portfólio com destaque se rota atual for portfolio --}}
                        <a class="hover:text-white transition hover:-translate-y-0.5 {{ request()->routeIs('contact') ? 'text-cyan-400' : '' }}" href="{{ route('contact') }}">Contato</a>
                        {{-- Link Contato com destaque se rota atual for contact --}}

                        <div class="h-5 w-px bg-white/10"></div>
                        {{-- Separador vertical entre links e botões de ação --}}

                        @auth
                            {{-- Se o usuário está logado --}}
                            @if(auth()->user()->role === 'admin')
                                {{-- Se o usuário logado tem role admin --}}
                                <a href="{{ route('admin.dashboard') }}"
                                   class="inline-flex items-center rounded-xl bg-white/10 px-5 py-2.5 text-white hover:bg-white/20 transition hover:scale-105 border border-white/5">
                                    Painel Admin
                                </a>
                                {{-- Botão/Link para dashboard do admin --}}
                            @else
                                {{-- Se não for admin (cliente) --}}
                                <a href="{{ route('client.dashboard') }}"
                                   class="inline-flex items-center rounded-xl bg-white/10 px-5 py-2.5 text-white hover:bg-white/20 transition hover:scale-105 border border-white/5">
                                    Acessar Portal
                                </a>
                                {{-- Botão/Link para dashboard do cliente --}}
                            @endif
                        @else
                            {{-- Se NÃO está logado --}}
                            <a href="{{ route('login') }}" class="hover:text-white transition font-semibold">Login</a>
                            {{-- Link para login --}}
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-5 py-2.5 font-bold text-slate-950 hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:brightness-110 transition active:scale-95">
                                Começar agora
                            </a>
                            {{-- Botão call-to-action para cadastro --}}
                        @endauth
                    </nav>

                    {{-- MOBILE MENU BUTTON --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-slate-300 hover:text-white focus:outline-none z-50">
                        {{-- 
                            Botão aparece só no mobile (md:hidden)
                            @click Alpine alterna mobileMenuOpen
                        --}}
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            {{-- Ícone do menu --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="!mobileMenuOpen" d="M4 6h16M4 12h16M4 18h16" />
                            {{-- Mostra "hamburger" quando menu está fechado --}}
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-show="mobileMenuOpen" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                            {{-- Mostra "X" quando menu está aberto (display none inicial pra evitar flash) --}}
                        </svg>
                    </button>
                </div>
            </div>

            {{-- MOBILE MENU DROPDOWN --}}
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden absolute top-full left-0 w-full bg-slate-950/95 backdrop-blur-xl border-b border-white/10 shadow-2xl py-6 px-6 flex flex-col gap-4"
                 style="display: none;">
                 {{-- 
                    Dropdown do menu mobile:
                    - x-show controla aparecer/sumir
                    - x-transition define animações (fade + slide)
                    - md:hidden: só mobile
                    - absolute top-full: aparece logo abaixo do header
                    - style display none: evita flash antes do Alpine iniciar
                 --}}
                 
                {{-- 🔥 Link SOBRE adicionado aqui --}}
                <a class="text-lg font-medium text-slate-300 hover:text-white" href="{{ route('sobre') }}">Sobre</a>
                {{-- Link Sobre no menu mobile --}}
                <a class="text-lg font-medium text-slate-300 hover:text-white" href="{{ route('services') }}">Serviços</a>
                {{-- Link Serviços no menu mobile --}}
                <a class="text-lg font-medium text-slate-300 hover:text-white" href="{{ route('faq') }}">FAQ</a>
                {{-- Link FAQ no menu mobile --}}
                <a class="text-lg font-medium text-slate-300 hover:text-white" href="{{ route('portfolio') }}">Portfólio</a>
                {{-- Link Portfólio no menu mobile --}}
                <a class="text-lg font-medium text-slate-300 hover:text-white" href="{{ route('contact') }}">Contato</a>
                {{-- Link Contato no menu mobile --}}
                
                <div class="h-px w-full bg-white/10 my-2"></div>
                {{-- Separador horizontal --}}

                @auth
                    {{-- Se está logado, mostra CTA para acessar --}}
                    <a href="{{ route('home') }}" class="w-full text-center rounded-xl bg-white/10 px-4 py-3 text-white font-bold">Acessar Portal</a>
                    {{-- Aqui está indo pra home. Se quiser, dá pra trocar por client.dashboard/admin.dashboard como no desktop --}}
                @else
                    {{-- Se não está logado --}}
                    <a href="{{ route('login') }}" class="text-center text-slate-300 hover:text-white py-2">Login</a>
                    {{-- Link Login --}}
                    <a href="{{ route('register') }}" class="w-full text-center rounded-xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-4 py-3 font-bold text-slate-950">Criar Conta</a>
                    {{-- Botão Cadastro --}}
                @endauth
            </div>
        </header>

        <main class="flex-1">
            {{-- Área principal. flex-1 faz ocupar o espaço entre header e footer --}}
            @yield('content')
            {{-- Injeta o conteúdo das páginas filhas --}}
        </main>

        {{-- MEGA FOOTER --}}
        <footer class="border-t border-white/10 bg-slate-950 relative overflow-hidden">
            {{-- Rodapé com borda superior sutil e fundo escuro --}}
            {{-- Glow Footer --}}
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-indigo-600/10 blur-[100px] pointer-events-none"></div>
            {{-- Glow central no rodapé para dar profundidade --}}

            <div class="max-w-7xl mx-auto px-6 py-16 relative z-10">
                {{-- Container central do footer --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                    {{-- Grid responsiva: 1 coluna no mobile, 2 no md, 4 no lg --}}
                    
                    {{-- Coluna 1: Marca & Status --}}
                    <div class="space-y-6">
                        {{-- Coluna com espaçamento vertical --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('images/logosuporteTI.png') }}" 
                                class="h-16 md:h-20 w-auto opacity-90 object-contain" alt="Logo">
                                {{-- Logo do footer, leve transparência com opacity-90 --}}

                                {{-- Fonte aumentada para text-3xl --}}
                                <div class="font-bold text-white text-2xl md:text-3xl leading-none">
                                Suporte TI
                                </div>
                                {{-- Nome da marca no footer --}}
                            </div>
                        </div>
                        
                        {{-- Status do Sistema --}}
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20">
                            {{-- "Badge" de status com verde e borda suave --}}
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                              {{-- Efeito ping (animação) atrás --}}
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                              {{-- Bolinha sólida na frente --}}
                            </span>
                            <span class="text-xs font-semibold text-emerald-400 uppercase tracking-wide">Sistemas Operacionais</span>
                            {{-- Texto do status (parece indicar "tudo ok") --}}
                        </div>
                    </div>

                    {{-- Coluna 2: Navegação --}}
                    <div>
                        <h4 class="font-bold text-white mb-6">Navegação</h4>
                        {{-- Título da coluna --}}
                        <ul class="space-y-3 text-sm text-slate-400">
                            {{-- Lista com links do site --}}
                            <li><a href="{{ route('home') }}" class="hover:text-cyan-400 transition">Início</a></li>
                            {{-- Link Início --}}
                            <li><a href="{{ route('sobre') }}" class="hover:text-cyan-400 transition">Sobre Nós</a></li> {{-- Adicionado aqui tb --}}
                            {{-- Link Sobre --}}
                            <li><a href="{{ route('services') }}" class="hover:text-cyan-400 transition">Serviços</a></li>
                            {{-- Link Serviços --}}
                            <li><a href="{{ route('portfolio') }}" class="hover:text-cyan-400 transition">Portfólio</a></li>
                            {{-- Link Portfólio --}}
                            <li><a href="{{ route('faq') }}" class="hover:text-cyan-400 transition">FAQ</a></li>
                            {{-- Link FAQ --}}
                        </ul>
                    </div>

                    {{-- Coluna 3: Legal & Suporte --}}
                    <div>
                        <h4 class="font-bold text-white mb-6">Legal</h4>
                        {{-- Título da coluna --}}
                        <ul class="space-y-3 text-sm text-slate-400">
                            {{-- Lista de páginas legais --}}
                            <li><a href="{{ route('terms') }}" class="hover:text-cyan-400 transition">Termos de Uso</a></li>
                            {{-- Termos --}}
                            <li><a href="{{ route('privacy') }}" class="hover:text-cyan-400 transition">Privacidade</a></li>
                            {{-- Privacidade --}}
                            <li><a href="{{ route('sla') }}" class="hover:text-cyan-400 transition">SLA</a></li>
                            {{-- SLA --}}
                        </ul>
                    </div>

                    {{-- Coluna 4: Contato & Redes Sociais --}}
                    <div>
                        <h4 class="font-bold text-white mb-6">Fale Conosco</h4>
                        {{-- Título da coluna --}}
                        <ul class="space-y-3 text-sm text-slate-400 mb-6">
                            {{-- Informações de contato --}}
                            <li class="flex items-center gap-2">
                                <span class="text-cyan-400">📧</span> contato@suporteti.com
                            </li>
                            {{-- Email --}}
                            <li class="flex items-center gap-2">
                                <span class="text-cyan-400">📍</span> Rio de Janeiro, RJ
                            </li>
                            {{-- Localização --}}
                        </ul>

                        {{-- REDES SOCIAIS --}}
                        <div class="flex gap-4">
                            {{-- Linha de botões sociais --}}
                            <a href="https://x.com/duu_beat" target="_blank" 
                               class="h-10 w-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:scale-110 transition"
                               aria-label="X (Twitter)">
                                {{-- Botão para X/Twitter, abre em nova aba --}}
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    {{-- Ícone do X --}}
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>

                            <a href="https://www.instagram.com/duu_beat/" target="_blank" 
                               class="h-10 w-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:scale-110 transition"
                               aria-label="Instagram">
                                {{-- Botão Instagram --}}
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    {{-- Ícone do Instagram --}}
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>

                            <a href="https://www.linkedin.com/in/eduardosilvadealmeida/" target="_blank" 
                               class="h-10 w-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white hover:scale-110 transition"
                               aria-label="LinkedIn">
                                {{-- Botão LinkedIn --}}
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    {{-- Ícone do LinkedIn --}}
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Copyright Line --}}
                <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs">
                    {{-- Linha final com copyright e versão --}}
                    <p class="text-slate-500">
                        &copy; {{ date('Y') }} Suporte TI Inc. CNPJ: 00.000.000/0001-00
                    </p>
                    {{-- Ano dinâmico via PHP date('Y') --}}
                    <div class="flex items-center gap-6 text-slate-500">
                        <span>Todos os direitos reservados.</span>
                        <span class="hidden md:inline text-slate-700">|</span>
                        {{-- separador só aparece no desktop --}}
                        <span>v2.0.0</span>
                        {{-- versão exibida do site --}}
                    </div>
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
