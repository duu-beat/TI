<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Geral de Suporte TI - {{ now()->format('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f1f5f9;
        }

        @media print {
            body {
                background-color: white;
                color: black;
            }
            .no-print {
                display: none;
            }
            .print-only {
                display: block;
            }
            .card {
                border: 1px solid #e2e8f0;
                box-shadow: none;
                background-color: white !important;
                color: black !important;
            }
            .text-slate-400, .text-slate-500 {
                color: #64748b !important;
            }
            table {
                color: black !important;
            }
            th {
                background-color: #f8fafc !important;
                color: black !important;
            }
            tr {
                border-bottom: 1px solid #e2e8f0 !important;
                page-break-inside: avoid;
            }
            header, .card {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="p-8 antialiased">
    
    {{-- Botão de Impressão (Apenas Tela) --}}
    <div class="no-print mb-8 flex justify-end">
        <button onclick="window.print()" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-900/20 transition-all active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Imprimir Relatório
        </button>
    </div>

    {{-- Cabeçalho --}}
    <header class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-white/10 pb-8">
        <div>
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2.5 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white">Relatório Geral de Suporte TI</h1>
            </div>
            <p class="text-slate-400 font-medium">Análise detalhada de performance e volumetria de chamados.</p>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Data de Emissão</p>
            <p class="text-lg font-mono text-indigo-300">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </header>

    {{-- Cards de Estatísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="card bg-slate-900/50 border border-white/10 p-6 rounded-3xl shadow-xl">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Total de Chamados</p>
            <p class="text-3xl font-black text-white">{{ $tickets->count() }}</p>
        </div>
        <div class="card bg-slate-900/50 border border-white/10 p-6 rounded-3xl shadow-xl">
            <p class="text-xs font-bold text-rose-400/80 uppercase tracking-wider mb-2">Vencidos (SLA)</p>
            <p class="text-3xl font-black text-rose-500">{{ $stats['overdue'] ?? 0 }}</p>
        </div>
        <div class="card bg-slate-900/50 border border-white/10 p-6 rounded-3xl shadow-xl">
            <p class="text-xs font-bold text-amber-400/80 uppercase tracking-wider mb-2">Vencem Hoje</p>
            <p class="text-3xl font-black text-amber-500">{{ $stats['due_today'] ?? 0 }}</p>
        </div>
        <div class="card bg-slate-900/50 border border-white/10 p-6 rounded-3xl shadow-xl">
            <p class="text-xs font-bold text-emerald-400/80 uppercase tracking-wider mb-2">Dentro do SLA</p>
            <p class="text-3xl font-black text-emerald-500">{{ $stats['within_sla_percent'] ?? 0 }}%</p>
        </div>
    </div>

    {{-- Tabela de Chamados --}}
    <div class="card bg-slate-900/50 border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 text-[10px] uppercase tracking-[0.2em] text-slate-500 font-black">
                    <th class="px-6 py-5">ID</th>
                    <th class="px-6 py-5">Data/Hora</th>
                    <th class="px-6 py-5">Cliente</th>
                    <th class="px-6 py-5">Assunto / Categoria</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-6 py-5">SLA</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 text-sm">
                @foreach($tickets as $ticket)
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4 font-mono text-slate-500 text-xs">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4">
                        <div class="text-white font-medium">{{ $ticket->created_at->format('d/m/Y') }}</div>
                        <div class="text-[10px] text-slate-500 font-mono">{{ $ticket->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-white font-semibold">{{ $ticket->user->name }}</div>
                        <div class="text-[10px] text-slate-500 truncate max-w-[150px]">{{ $ticket->user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-200 font-medium truncate max-w-xs mb-1">{{ $ticket->subject }}</div>
                        <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-slate-800 text-slate-400 border border-white/5">
                            {{ $ticket->category ?? 'Geral' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider
                            {{ $ticket->status->value === 'RESOLVED' || $ticket->status->value === 'CLOSED' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 
                               ($ticket->status->value === 'IN_PROGRESS' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-slate-800 text-slate-400 border border-white/5') }}">
                            {{ $ticket->status->label() }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($ticket->sla_due_at && !in_array($ticket->status->value, ['RESOLVED', 'CLOSED']))
                            <div class="flex items-center gap-1.5 font-black text-[10px] uppercase tracking-tighter
                                {{ $ticket->sla_status === 'danger' ? 'text-rose-400' : 
                                   ($ticket->sla_status === 'warning' ? 'text-amber-400' : 'text-emerald-400') }}">
                                {{ $ticket->sla_remaining }}
                            </div>
                        @else
                            <span class="text-slate-600">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Rodapé --}}
    <footer class="mt-12 text-center text-slate-500 text-[10px] uppercase tracking-[0.3em] font-bold pb-12">
        Sistema de Inventário e Suporte TI &copy; {{ date('Y') }}
    </footer>

</body>
</html>
