<div>
    <style>
        .grid-input {
            width: 100%;
            border: none;
            background: transparent;
            text-align: center;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 0.25rem;
            color: #1e293b;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .grid-input:focus {
            outline: none;
            background-color: #f0f9ff;
            box-shadow: inset 0 0 0 2px #3b82f6 !important;
            border-radius: 4px;
            z-index: 10;
        }

        .grid-input::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }

        /* Hide number spin buttons */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .challan-table {
            border-spacing: 0;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .challan-table th, .challan-table td {
            border: 1px solid #e2e8f0;
        }

        .row-index {
            background-color: #f8fafc;
            color: #94a3b8;
            font-size: 0.7rem;
            font-weight: 800;
            width: 32px;
            text-align: center;
            border-right: 2px solid #e2e8f0 !important;
        }

        tr:hover td:not(.row-index) {
            background-color: #fdfdfd;
        }

        @media (max-width: 640px) {
            .grid-input {
                font-size: 0.75rem;
                padding: 0.5rem 0.1rem;
            }
            .row-index {
                width: 24px;
            }
        }
    </style>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" 
         x-data="{ 
            grid: @entangle('grid'),
            init() {
                // Initial calculation if needed
            }
         }">
        
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="challan-table">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="row-index py-2">#</th>
                        @for($c = 1; $c <= 6; $c++)
                            <th class="py-2 text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $c }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach(range(1, 12) as $r)
                        <tr>
                            <td class="row-index">{{ $r }}</td>
                            @foreach(range(1, 6) as $c)
                                <td class="p-0">
                                    <input type="number" 
                                           step="0.01" 
                                           x-model="grid[{{ $r }}][{{ $c }}]"
                                           @input.debounce.500ms="$wire.calculateTotals()"
                                           class="grid-input"
                                           placeholder="-">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-blue-50/50">
                    <tr class="font-bold text-blue-700">
                        <td class="row-index">Σ</td>
                        @foreach(range(1, 6) as $c)
                            <td class="text-center py-2 text-xs">
                                <span x-text="($wire.column_totals[{{ $c }}] || 0).toFixed(1)"></span>
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Summary Footer -->
        <div class="grid grid-cols-2 divide-x divide-gray-200 bg-slate-50 border-t border-gray-200">
            <div class="p-4 text-center">
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">Total Pieces</p>
                <p class="text-2xl font-black text-slate-800" x-text="$wire.total_pieces">0</p>
            </div>
            <div class="p-4 text-center">
                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-widest mb-1">Total Metres</p>
                <p class="text-2xl font-black text-blue-600">
                    <span x-text="($wire.total_meters || 0).toFixed(1)">0.0</span>
                </p>
            </div>
        </div>
    </div>
</div>