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

        /* tfoot column totals */
        .col-total-cell {
            text-align: center;
            padding: 8px 4px;
            background: linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
        }
        .col-total-val {
            display: inline-block;
            font-size: 0.72rem;
            font-weight: 800;
            /* color: #1d4ed8; */
            letter-spacing: 0.01em;
            /* background: #dbeafe; */
            border-radius: 6px;
            padding: 2px 6px;
            min-width: 48px;
        }

        /* summary footer */
        .challan-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .summary-card {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 14px 12px;
        }
        .summary-card-pieces {
            border-right: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        .summary-card-metres {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }
        .summary-accent {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
        }
        .summary-label-row {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .summary-icon {
            width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .summary-label {
            font-size: 8.5px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .summary-value {
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .summary-unit {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 999px;
            padding: 2px 8px;
        }

        @media (max-width: 640px) {
            .grid-input {
                font-size: 0.75rem;
                padding: 0.5rem 0.1rem;
            }
            .row-index {
                width: 24px;
            }
            .summary-value {
                font-size: 1.25rem;
            }
            .col-total-val {
                font-size: 0.65rem;
                min-width: 36px;
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
                <tfoot>
                    <tr>
                        <td class="row-index" style="background:linear-gradient(135deg,#eff6ff,#e0f2fe); color:#1d4ed8; font-size:0.75rem; font-weight:900;">Σ</td>
                        @foreach(range(1, 6) as $c)
                            <td class="col-total-cell">
                                <span class="col-total-val" x-text="($wire.column_totals[{{ $c }}] || 0).toFixed(1)">0.0</span>
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Separator -->
        <div style="height:10px; background:#f1f5f9; border-top:2px solid #e2e8f0; border-bottom:2px solid #e2e8f0;"></div>

        <!-- Summary Footer -->
        <div class="challan-summary">

            {{-- Total Pieces --}}
            <div class="summary-card summary-card-pieces">
                <div class="summary-accent" style="background:linear-gradient(90deg,#94a3b8,#475569,#94a3b8);"></div>
                <div class="summary-label-row">
                    <div class="summary-icon" style="background:#e2e8f0;">
                        <svg width="10" height="10" fill="none" stroke="#475569" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 6h14M5 18h14"/>
                        </svg>
                    </div>
                    <span class="summary-label" style="color:#64748b;">Total Pieces</span>
                </div>
                <span class="summary-value" style="color:#1e293b;" x-text="$wire.total_pieces">0</span>
                <span class="summary-unit" style="color:#94a3b8; background:#e2e8f0;">pcs</span>
            </div>

            {{-- Total Metres --}}
            <div class="summary-card summary-card-metres">
                <div class="summary-accent" style="background:linear-gradient(90deg,#93c5fd,#2563eb,#6366f1);"></div>
                <div class="summary-label-row">
                    <div class="summary-icon" style="background:#bfdbfe;">
                        <svg width="10" height="10" fill="none" stroke="#2563eb" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/>
                        </svg>
                    </div>
                    <span class="summary-label" style="color:#2563eb;">Total Metres</span>
                </div>
                <span class="summary-value" style="color:#1d4ed8;">
                    <span x-text="($wire.total_meters || 0).toFixed(1)">0.0</span>
                </span>
                <span class="summary-unit" style="color:#2563eb; background:#bfdbfe;">mtrs</span>
            </div>

        </div>
    </div>
</div>