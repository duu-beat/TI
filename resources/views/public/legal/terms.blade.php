@extends('layouts.site')

@section('content')
<div class="relative py-20">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full z-0 pointer-events-none">
        <div class="absolute top-[10%] left-[20%] w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold text-white mb-2">Termos de Uso</h1>
            <p class="text-slate-400">Última atualização: {{ date('d/m/Y') }}</p>
        </div>

        <div class="rounded-3xl border border-white/10 bg-slate-900/50 backdrop-blur-xl p-8 md:p-12">
            <article class="prose prose-invert prose-headings:text-white prose-p:text-slate-400 prose-a:text-cyan-400 prose-li:text-slate-400 max-w-none">
                <h3>1. Aceitação dos Termos</h3>
                <p>Ao acessar e usar a plataforma Suporte TI, você concorda em cumprir estes termos de serviço, todas as leis e regulamentos aplicáveis.</p>

                <h3>2. Uso da Licença</h3>
                <p>É concedida permissão para acessar o painel de suporte para uso pessoal e comercial vinculado aos serviços contratados. Esta é a concessão de uma licença, não uma transferência de título.</p>

                <h3>3. Responsabilidades do Usuário</h3>
                <ul>
                    <li>Manter a confidencialidade de sua senha e conta.</li>
                    <li>Fornecer informações precisas sobre os problemas técnicos.</li>
                    <li>Não usar o serviço para fins ilegais ou não autorizados.</li>
                </ul>

                <h3>4. Limitações</h3>
                <p>Em nenhum caso o Suporte TI ou seus fornecedores serão responsáveis por quaisquer danos (incluindo, sem limitação, danos por perda de dados ou lucro) decorrentes do uso ou da incapacidade de usar os materiais em nosso site.</p>
                
                <h3>5. Modificações</h3>
                <p>O Suporte TI pode revisar estes termos de serviço a qualquer momento, sem aviso prévio. Ao usar este site, você concorda em ficar vinculado à versão atual desses termos de serviço.</p>
            </article>
        </div>
    </div>
</div>
@endsection