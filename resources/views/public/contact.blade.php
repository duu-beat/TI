@extends('layouts.public')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-14">
    <div class="grid lg:grid-cols-2 gap-10 items-start">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-white">Contato</h1>
            <p class="mt-2 text-slate-300 max-w-xl">
                Me chama com o seu problema e eu te respondo com o melhor caminho. Sem enrolação, sem susto no orçamento.
            </p>

            <div class="mt-8 space-y-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-white font-semibold">Atendimento</div>
                    <p class="mt-1 text-slate-300 text-sm">Remoto e presencial (dependendo da região).</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="text-white font-semibold">Horários</div>
                    <p class="mt-1 text-slate-300 text-sm">Seg a Sáb, combinando certinho por mensagem.</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/5 p-6 shadow-sm">
            <form class="space-y-4" method="POST" action="#">
                @csrf

                <div>
                    <label class="text-sm text-slate-300">Nome</label>
                    <input name="name" type="text"
                        class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400/60 focus:ring-cyan-400/20"
                        placeholder="Seu nome" />
                </div>

                <div>
                    <label class="text-sm text-slate-300">Email</label>
                    <input name="email" type="email"
                        class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400/60 focus:ring-cyan-400/20"
                        placeholder="seuemail@exemplo.com" />
                </div>

                <div>
                    <label class="text-sm text-slate-300">Mensagem</label>
                    <textarea name="message" rows="5"
                        class="mt-2 w-full rounded-xl border border-white/10 bg-slate-950/60 px-4 py-3 text-slate-100 placeholder:text-slate-500 focus:border-cyan-400/60 focus:ring-cyan-400/20"
                        placeholder="Descreva o problema, modelo do PC e o que já tentou..."></textarea>
                </div>

                <button type="submit"
                    class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 to-cyan-400 px-6 py-3 font-semibold text-slate-950 hover:opacity-95 transition">
                    Enviar
                </button>

                <p class="text-xs text-slate-400">
                    Ao enviar, você concorda em ser contatado para orçamento e suporte.
                </p>
            </form>
        </div>
    </div>
</section>
@endsection
