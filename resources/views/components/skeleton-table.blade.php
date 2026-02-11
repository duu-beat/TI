@props(['rows' => 5, 'columns' => 4])

{{-- Skeleton para tabelas --}}
<div class="animate-pulse">
    <div class="overflow-x-auto">
        <table class="min-w-full">
            {{-- Header --}}
            <thead class="bg-slate-900/60 border-b border-white/10">
                <tr>
                    @for($i = 0; $i < $columns; $i++)
                        <th class="px-6 py-4 text-left">
                            <div class="h-4 bg-slate-700/50 rounded w-24"></div>
                        </th>
                    @endfor
                </tr>
            </thead>
            
            {{-- Body --}}
            <tbody class="divide-y divide-white/5">
                @for($r = 0; $r < $rows; $r++)
                    <tr class="hover:bg-white/5">
                        @for($c = 0; $c < $columns; $c++)
                            <td class="px-6 py-4">
                                @if($c === 0)
                                    {{-- Primeira coluna geralmente é ID ou nome --}}
                                    <div class="h-4 bg-slate-700/50 rounded w-32"></div>
                                @elseif($c === $columns - 1)
                                    {{-- Última coluna geralmente é ações --}}
                                    <div class="flex gap-2">
                                        <div class="h-8 w-8 bg-slate-700/50 rounded"></div>
                                        <div class="h-8 w-8 bg-slate-700/50 rounded"></div>
                                    </div>
                                @else
                                    {{-- Colunas do meio --}}
                                    <div class="h-4 bg-slate-700/50 rounded w-20"></div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
