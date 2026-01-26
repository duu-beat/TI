@extends('layouts.site')

@section('content')
<div class="relative py-20">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute bottom-[20%] right-[20%] w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-white mb-2">Política de Privacidade</h1>
            <p class="text-slate-400">Como cuidamos dos seus dados.</p>
        </div>

        <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-12">
            <article class="prose prose-invert prose-headings:text-white prose-p:text-slate-400 prose-a:text-cyan-400 prose-li:text-slate-400 max-w-none">
                <h3>1. Coleta de Dados</h3>
                <p>Solicitamos informações pessoais apenas quando realmente precisamos delas para lhe fornecer um serviço. Fazemo-lo por meios justos e legais, com o seu conhecimento e consentimento.</p>

                <h3>2. Uso das Informações</h3>
                <p>Usamos seus dados para:</p>
                <ul>
                    <li>Identificação no sistema de chamados.</li>
                    <li>Contato para resolução de problemas técnicos.</li>
                    <li>Envio de notificações sobre o status dos serviços.</li>
                </ul>

                <h3>3. Retenção de Dados</h3>
                <p>Apenas retemos as informações coletadas pelo tempo necessário para fornecer o serviço solicitado. Quando armazenamos dados, protegemos dentro de meios comercialmente aceitáveis para evitar perdas e roubos.</p>

                <h3>4. Compartilhamento</h3>
                <p>Não compartilhamos informações de identificação pessoal publicamente ou com terceiros, exceto quando exigido por lei.</p>
            </article>
        </div>
    </div>
</div>
@endsection