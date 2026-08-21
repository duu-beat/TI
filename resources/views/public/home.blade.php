@extends('layouts.site')

@section('title', 'Suporte TI | Operação, segurança e continuidade para sua empresa')
@section('meta_description', 'Centralize suporte, inventário, segurança e conhecimento em uma única operação de TI. Acompanhe chamados, SLA, ativos e governança com clareza.')

@section('content')
    <section class="relative overflow-hidden" aria-labelledby="home-hero-title">
        <div class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[34rem] bg-gradient-to-b from-indigo-500/[0.09] via-slate-950/20 to-transparent"></div>
        <div class="mx-auto max-w-7xl px-6 pb-20 pt-16 sm:pb-28 sm:pt-24 lg:px-8">
            <div class="mx-auto max-w-4xl text-center">
                <p class="inline-flex items-center gap-2 rounded-full border border-indigo-400/20 bg-indigo-500/10 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-indigo-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-cyan-300 shadow-[0_0_10px_rgba(103,232,249,0.9)]"></span>
                    Operação de TI em um só lugar
                </p>
                <h1 id="home-hero-title" class="mt-7 text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-7xl">
                    TI que funciona.
                    <span class="block bg-gradient-to-r from-indigo-300 via-cyan-300 to-emerald-300 bg-clip-text text-transparent">Negócio que segue em frente.</span>
                </h1>
                <p class="mx-auto mt-7 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                    Organize chamados, acompanhe prazos, proteja acessos e mantenha seus ativos sob controle com uma experiência simples para quem solicita, atende e supervisiona.
                </p>
                <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-7 py-4 text-sm font-bold text-slate-950 transition hover:brightness-110 hover:shadow-[0_0_28px_rgba(34,211,238,0.25)] focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:ring-offset-2 focus:ring-offset-slate-950">
                        Criar conta
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/[0.04] px-7 py-4 text-sm font-bold text-white transition hover:border-white/20 hover:bg-white/[0.08] focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-slate-950">
                        Falar com especialista
                    </a>
                </div>
            </div>

            <div class="mx-auto mt-14 grid max-w-5xl gap-4 sm:grid-cols-3">
                <article class="rounded-2xl border border-white/10 bg-slate-900/55 p-5 text-left shadow-xl shadow-slate-950/15 backdrop-blur-sm">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-400/20 bg-indigo-500/10 text-indigo-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></div>
                    <h2 class="mt-4 text-sm font-bold text-white">Atendimento com contexto</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Chamados, anexos, atualizações e histórico reunidos no mesmo fluxo.</p>
                </article>
                <article class="rounded-2xl border border-white/10 bg-slate-900/55 p-5 text-left shadow-xl shadow-slate-950/15 backdrop-blur-sm">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-cyan-400/20 bg-cyan-500/10 text-cyan-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6h12Z" /></svg></div>
                    <h2 class="mt-4 text-sm font-bold text-white">Segurança e governança</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">2FA, auditoria, SLA e tratamento controlado de incidentes críticos.</p>
                </article>
                <article class="rounded-2xl border border-white/10 bg-slate-900/55 p-5 text-left shadow-xl shadow-slate-950/15 backdrop-blur-sm">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-emerald-400/20 bg-emerald-500/10 text-emerald-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M7 4v6m10-6v6M6 12h12v7H6z" /></svg></div>
                    <h2 class="mt-4 text-sm font-bold text-white">Ativos rastreáveis</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-400">Inventário, QR Code e termos digitais para cada movimentação importante.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="border-y border-white/5 bg-slate-900/35 py-20 sm:py-24" aria-labelledby="services-title">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-cyan-300">Uma operação mais clara</p>
                <h2 id="services-title" class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Tudo o que sua equipe precisa para manter a TI em movimento.</h2>
                <p class="mt-5 text-base leading-7 text-slate-400">O sistema foi pensado para reduzir ruído operacional, dar visibilidade à liderança e tornar o suporte mais simples para cada pessoa atendida.</p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-2">
                <article class="group rounded-3xl border border-white/10 bg-slate-950/45 p-6 transition hover:border-indigo-400/30 hover:bg-slate-900/65 sm:p-7">
                    <div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2a4 4 0 0 1 4-4h5m0 0-3-3m3 3-3 3M5 19V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2" /></svg></span><div><h3 class="text-lg font-bold text-white">Chamados que evoluem com o atendimento</h3><p class="mt-2 text-sm leading-6 text-slate-400">Abertura guiada, prévia de anexos, histórico em linha do tempo, respostas internas e comunicação clara com quem solicitou.</p></div></div>
                </article>
                <article class="group rounded-3xl border border-white/10 bg-slate-950/45 p-6 transition hover:border-cyan-400/30 hover:bg-slate-900/65 sm:p-7">
                    <div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></span><div><h3 class="text-lg font-bold text-white">SLA que deixa os riscos visíveis</h3><p class="mt-2 text-sm leading-6 text-slate-400">Contagem regressiva, alertas preventivos, escalonamento e relatórios para que prazos não virem surpresas.</p></div></div>
                </article>
                <article class="group rounded-3xl border border-white/10 bg-slate-950/45 p-6 transition hover:border-emerald-400/30 hover:bg-slate-900/65 sm:p-7">
                    <div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13h2l2-6 4 12 3-8 2 2h5" /></svg></span><div><h3 class="text-lg font-bold text-white">Inventário com responsabilidade</h3><p class="mt-2 text-sm leading-6 text-slate-400">Ativos identificados por QR Code, histórico de movimentação e termos digitais assinados no celular ou tablet.</p></div></div>
                </article>
                <article class="group rounded-3xl border border-white/10 bg-slate-950/45 p-6 transition hover:border-violet-400/30 hover:bg-slate-900/65 sm:p-7">
                    <div class="flex items-start gap-4"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-500/10 text-violet-200"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.25a8.5 8.5 0 0 0-7.42 4.35.8.8 0 0 0 0 .8A8.5 8.5 0 0 0 12 15.75a8.5 8.5 0 0 0 7.42-4.35.8.8 0 0 0 0-.8A8.5 8.5 0 0 0 12 6.25ZM12 13a2 2 0 1 1 0-4 2 2 0 0 1 0 4Z" /></svg></span><div><h3 class="text-lg font-bold text-white">Conhecimento que reduz demanda repetitiva</h3><p class="mt-2 text-sm leading-6 text-slate-400">Artigos publicados, busca por categoria e sugestões contextuais antes mesmo da abertura de um chamado.</p></div></div>
                </article>
            </div>
        </div>
    </section>

    <section class="py-20 sm:py-24" aria-labelledby="flow-title">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-300">Do pedido à solução</p>
                    <h2 id="flow-title" class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Um fluxo simples para quem solicita, atende e supervisiona.</h2>
                    <p class="mt-5 max-w-xl text-base leading-7 text-slate-400">Cada área recebe apenas o que precisa. Clientes acompanham seus próprios pedidos, Admins cuidam da operação e o Master enxerga riscos, acessos, auditoria e saúde do ambiente.</p>
                    <a href="{{ route('services') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-bold text-cyan-300 transition hover:text-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-4 focus:ring-offset-slate-950">Conhecer os serviços <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7" /></svg></a>
                </div>
                <ol class="space-y-4" aria-label="Etapas de atendimento">
                    <li class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/55 p-5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-500/15 text-sm font-black text-indigo-200">01</span><div><h3 class="font-bold text-white">Solicite com clareza</h3><p class="mt-1 text-sm leading-6 text-slate-400">Abra um chamado, detalhe o contexto e anexe fotos, PDFs ou outros arquivos necessários.</p></div></li>
                    <li class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/55 p-5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-cyan-500/15 text-sm font-black text-cyan-200">02</span><div><h3 class="font-bold text-white">Acompanhe com transparência</h3><p class="mt-1 text-sm leading-6 text-slate-400">Status, prazo de SLA, mensagens e decisões técnicas ficam registrados no mesmo lugar.</p></div></li>
                    <li class="flex gap-4 rounded-2xl border border-white/10 bg-slate-900/55 p-5"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-500/15 text-sm font-black text-emerald-200">03</span><div><h3 class="font-bold text-white">Melhore com governança</h3><p class="mt-1 text-sm leading-6 text-slate-400">Relatórios, auditoria, indicadores e Base de Conhecimento ajudam a reduzir recorrências.</p></div></li>
                </ol>
            </div>
        </div>
    </section>

    <section class="border-y border-white/5 bg-gradient-to-r from-indigo-500/[0.08] via-slate-900/65 to-cyan-500/[0.07] py-20 sm:py-24" aria-labelledby="governance-title">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:px-8">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-violet-200">Projetado para continuidade</p>
                <h2 id="governance-title" class="mt-3 text-3xl font-black tracking-tight text-white sm:text-4xl">Segurança não precisa ficar separada da operação.</h2>
                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">O mesmo ambiente que organiza o atendimento também ajuda a proteger identidades, registrar mudanças, acompanhar incidentes críticos e dar visibilidade para decisões de governança.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                <div class="rounded-2xl border border-violet-400/15 bg-slate-950/40 p-4"><p class="text-sm font-bold text-white">Acessos protegidos</p><p class="mt-1 text-sm leading-6 text-slate-400">Autenticação em dois fatores para o núcleo de segurança e perfis privilegiados monitorados.</p></div>
                <div class="rounded-2xl border border-amber-400/15 bg-slate-950/40 p-4"><p class="text-sm font-bold text-white">Decisões auditáveis</p><p class="mt-1 text-sm leading-6 text-slate-400">Eventos relevantes e resoluções críticas deixam rastreabilidade para a gestão.</p></div>
                <div class="rounded-2xl border border-emerald-400/15 bg-slate-950/40 p-4"><p class="text-sm font-bold text-white">Ativos com termo digital</p><p class="mt-1 text-sm leading-6 text-slate-400">Entregas e devoluções registradas com assinatura e PDF armazenado de forma privada.</p></div>
            </div>
        </div>
    </section>

    <section class="py-20 sm:py-28" aria-labelledby="home-cta-title">
        <div class="mx-auto max-w-4xl px-6 text-center lg:px-8">
            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-cyan-300">Próximo passo</p>
            <h2 id="home-cta-title" class="mt-3 text-3xl font-black tracking-tight text-white sm:text-5xl">Mais clareza para sua TI começa com uma boa operação.</h2>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-400">Conheça a solução, fale com a equipe ou crie sua conta para começar a organizar os fluxos que mais impactam seu dia a dia.</p>
            <div class="mt-9 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-7 py-4 text-sm font-bold text-slate-950 transition hover:bg-cyan-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-slate-950">Falar com especialista</a>
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/[0.04] px-7 py-4 text-sm font-bold text-white transition hover:bg-white/[0.09] focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-slate-950">Criar conta</a>
            </div>
        </div>
    </section>
@endsection
