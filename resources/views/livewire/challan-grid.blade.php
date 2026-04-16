<div class="w-full space-y-5">
    {{-- Absolute Centering & Professional Grid Styles --}}
    <style>
        /* Modern aesthetic fixes */
        .grid-input:focus {
            background-color: #f0f9ff !important;
            box-shadow: inset 0 0 0 2px #3b82f6;
            z-index: 10;
        }
        /* Remove number spinners for perfect centering */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
            text-align: center !important;
        }
        .header-gradient {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .row-hover:hover {
            background-color: #f9fafb !important;
        }
    </style>

    <div class="relative overflow-hidden">
        {{-- Restored User's Preferred Container Classes --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-white">
            <table class="w-full border-collapse" style="table-layout: fixed; min-width: 320px;">
                <thead>
                    <tr class="header-gradient border-b border-gray-200">
                        <th class="w-[30px] sm:w-[50px] py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-tight">
                            #
                        </th>
                        @for ($c = 1; $c <= 6; $c++)
                            <th class="py-4 text-center text-[10px] sm:text-[11px] font-black text-slate-600 uppercase tracking-tighter border-l border-gray-100">
                                <span>{{ $c }}</span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @for ($r = 1; $r <= 12; $r++)
                        <tr class="row-hover transition-colors">
                            <td class="py-3 text-center text-[10px] sm:text-xs font-bold text-slate-400 bg-slate-50/50">
                                {{ $r }}
                            </td>
                            @for ($c = 1; $c <= 6; $c++)
                                <td class="p-0 border-l border-gray-100 align-middle">
                                    <input type="number"
                                           step="0.01"
                                           wire:model.live="grid.{{ $r }}.{{ $c }}"
                                           class="grid-input w-full border-0 bg-transparent py-4 text-center text-[12px] sm:text-sm font-bold text-slate-800 focus:ring-0 focus:outline-none placeholder-slate-200"
                                           placeholder="0">
                                </td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 border-t-2 border-slate-200">
                        <td class="py-4 text-center text-xs font-black text-slate-500">
                            Σ
                        </td>
                        @for ($c = 1; $c <= 6; $c++)
                            <td class="py-4 text-center border-l border-gray-200">
                                <span class="text-[11px] sm:text-xs font-black text-blue-700 block text-center">
                                    {{ number_format($column_totals[$c] ?? 0, 1) }}
                                </span>
                            </td>
                        @endfor
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Premium Minimalist Summary Row --}}
    <div class="flex flex-row w-full bg-white border border-gray-200 rounded-xl overflow-hidden divide-x divide-gray-100 shadow-sm">
        <div class="flex-1 py-4 flex flex-col items-center justify-center bg-gradient-to-br from-white to-slate-50">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Pieces</span>
            <div class="flex items-center space-x-2 mt-1">
                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ number_format($total_pieces) }}</h3>
            </div>
        </div>
        
        <div class="flex-1 py-4 flex flex-col items-center justify-center bg-gradient-to-br from-white to-slate-50">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Metres</span>
            <div class="flex items-center space-x-2 mt-1">
                <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ number_format($total_meters, 1) }}</h3>
            </div>
        </div>
    </div>
</div>
