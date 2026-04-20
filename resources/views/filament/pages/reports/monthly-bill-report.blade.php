<x-filament-panels::page>
<style>
    /* ── Report Page ── */
    .rpt-page { display: flex; flex-direction: column; gap: 1.25rem; }

    /* ── Filter Bar ── */
    .rpt-filter-bar {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        box-shadow: 0 1px 4px rgba(15,23,42,.06);
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        gap: 1rem;
    }
    .rpt-filter-group { display: flex; flex-direction: column; gap: 4px; }
    .rpt-filter-label {
        font-size: 10.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .6px;
    }
    .rpt-select {
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 13.5px;
        color: #0f172a;
        background: #f8fafc;
        cursor: pointer;
        outline: none;
        transition: border-color .15s;
        min-width: 140px;
    }
    .rpt-select:focus { border-color: #3b82f6; background: #fff; }

    .rpt-period-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        color: #1d4ed8;
    }
    .rpt-period-badge svg { flex-shrink: 0; }

    .rpt-export-group { display: flex; gap: 8px; margin-left: auto; }
    .rpt-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: opacity .15s, transform .1s;
        border: none;
    }
    .rpt-btn:hover { opacity: .88; transform: translateY(-1px); }
    .rpt-btn-excel { background: #059669; color: #fff; }
    .rpt-btn-pdf   { background: #dc2626; color: #fff; }
    .rpt-btn svg   { flex-shrink: 0; }

    /* ── Summary Cards ── */
    .rpt-stats-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
    }
    @media (max-width: 1200px) { .rpt-stats-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 640px)  { .rpt-stats-grid { grid-template-columns: repeat(2, 1fr); } }

    .rpt-stat-card {
        border-radius: 12px;
        padding: 14px 12px;
        text-align: center;
        border: 1.5px solid;
    }
    .rpt-stat-icon { display: flex; justify-content: center; margin-bottom: 8px; }
    .rpt-stat-value { font-size: 18px; font-weight: 800; line-height: 1.1; margin-bottom: 4px; }
    .rpt-stat-label { font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }

    .rpt-stat-blue   { background: #eff6ff; border-color: #bfdbfe; }
    .rpt-stat-blue .rpt-stat-value  { color: #1d4ed8; }
    .rpt-stat-blue .rpt-stat-label  { color: #60a5fa; }

    .rpt-stat-indigo { background: #eef2ff; border-color: #c7d2fe; }
    .rpt-stat-indigo .rpt-stat-value { color: #4338ca; font-size: 15px; }
    .rpt-stat-indigo .rpt-stat-label { color: #818cf8; }

    .rpt-stat-green  { background: #f0fdf4; border-color: #bbf7d0; }
    .rpt-stat-green .rpt-stat-value  { color: #16a34a; font-size: 14px; }
    .rpt-stat-green .rpt-stat-label  { color: #4ade80; }

    .rpt-stat-amber  { background: #fffbeb; border-color: #fde68a; }
    .rpt-stat-amber .rpt-stat-value  { color: #d97706; font-size: 14px; }
    .rpt-stat-amber .rpt-stat-label  { color: #fbbf24; }

    .rpt-stat-orange { background: #fff7ed; border-color: #fed7aa; }
    .rpt-stat-orange .rpt-stat-value { color: #ea580c; font-size: 14px; }
    .rpt-stat-orange .rpt-stat-label { color: #fb923c; }

    .rpt-stat-red    { background: #fef2f2; border-color: #fecaca; }
    .rpt-stat-red .rpt-stat-value    { color: #dc2626; font-size: 14px; }
    .rpt-stat-red .rpt-stat-label    { color: #f87171; }

    /* ── Panel / Card ── */
    .rpt-panel {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(15,23,42,.06);
    }
    .rpt-panel-head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .rpt-panel-head h3 {
        margin: 0;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: .6px;
    }
    .rpt-panel-head svg { color: #3b82f6; flex-shrink: 0; }
    .rpt-badge-count {
        margin-left: auto;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 11px;
        font-weight: 700;
        padding: 2px 10px;
        border-radius: 999px;
    }

    /* ── Empty State ── */
    .rpt-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
        color: #94a3b8;
    }
    .rpt-empty svg { margin-bottom: 12px; }
    .rpt-empty p { margin: 0; font-size: 14px; font-weight: 500; color: #64748b; }
    .rpt-empty span { font-size: 12px; color: #94a3b8; margin-top: 4px; display: block; }

    /* ── Table ── */
    .rpt-table-wrap { overflow-x: auto; }
    .rpt-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }
    .rpt-table thead tr {
        background: #f1f5f9;
        border-bottom: 2px solid #e2e8f0;
    }
    .rpt-table thead th {
        padding: 10px 12px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .5px;
        white-space: nowrap;
    }
    .rpt-table thead th.th-right { text-align: right; }
    .rpt-table thead th.th-center { text-align: center; }

    .rpt-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
    .rpt-table tbody tr:nth-child(even) { background: #fafbfc; }
    .rpt-table tbody tr:hover { background: #eff6ff; }

    .rpt-table tbody td { padding: 9px 12px; color: #1e293b; vertical-align: middle; }
    .rpt-table tbody td.td-right { text-align: right; font-family: 'Courier New', monospace; }
    .rpt-table tbody td.td-center { text-align: center; }
    .rpt-table tbody td.td-num { text-align: right; font-family: 'Courier New', monospace; font-size: 12.5px; }

    .rpt-table tfoot tr { background: #eff6ff; border-top: 2px solid #bfdbfe; }
    .rpt-table tfoot td {
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 700;
        color: #1d4ed8;
        font-family: 'Courier New', monospace;
    }
    .rpt-table tfoot td.tf-label {
        text-align: right;
        font-family: inherit;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-size: 12px;
    }

    /* Badges */
    .badge-bill {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 11.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 5px;
        font-family: 'Courier New', monospace;
    }
    .badge-challan {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        font-size: 11.5px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 5px;
    }
    .text-muted { color: #94a3b8; }
    .text-green  { color: #16a34a; }
    .text-amber  { color: #d97706; }
    .text-orange { color: #ea580c; }
    .text-blue   { color: #1d4ed8; }
    .font-bold   { font-weight: 700; }
    .sub-text    { font-size: 11px; color: #94a3b8; font-family: monospace; }

    /* ── Customer Breakdown ── */
    .rpt-progress-wrap { display: flex; align-items: center; gap: 8px; justify-content: flex-end; }
    .rpt-progress-bar  {
        width: 80px; height: 6px; background: #e2e8f0; border-radius: 999px; overflow: hidden; flex-shrink: 0;
    }
    .rpt-progress-fill { height: 100%; background: #3b82f6; border-radius: 999px; }
    .rpt-pct { font-size: 11.5px; font-weight: 600; color: #64748b; width: 38px; text-align: right; }
    .cust-rank {
        display: inline-flex; align-items: center; justify-content: center;
        width: 24px; height: 24px; border-radius: 50%;
        background: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700;
    }
</style>

<div class="rpt-page">

    {{-- ── FILTER BAR ── --}}
    <div class="rpt-filter-bar">

        <div class="rpt-filter-group">
            <span class="rpt-filter-label">Month</span>
            <select wire:model.live="month" class="rpt-select">
                @php $months = [1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',
                                7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December']; @endphp
                @foreach($months as $m => $mName)
                    <option value="{{ $m }}">{{ $mName }}</option>
                @endforeach
            </select>
        </div>

        <div class="rpt-filter-group">
            <span class="rpt-filter-label">Year</span>
            <select wire:model.live="year" class="rpt-select">
                @foreach($this->getAvailableYears() as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;align-items:center;padding-bottom:2px;">
            <span class="rpt-period-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                {{ $this->getPeriodLabel() }}
            </span>
        </div>

        <div class="rpt-export-group">
            <a href="{{ $this->getExcelUrl() }}" target="_blank" class="rpt-btn rpt-btn-excel">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="8" y1="13" x2="16" y2="13"></line>
                    <line x1="8" y1="17" x2="16" y2="17"></line>
                    <line x1="10" y1="9" x2="8" y2="9"></line>
                </svg>
                Export Excel
            </a>
            <a href="{{ $this->getPdfUrl() }}" target="_blank" class="rpt-btn rpt-btn-pdf">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="12" y1="18" x2="12" y2="12"></line>
                    <polyline points="9 15 12 18 15 15"></polyline>
                </svg>
                Export PDF
            </a>
        </div>

    </div>

    {{-- ── SUMMARY CARDS ── --}}
    @php $s = $this->summary; @endphp
    <div class="rpt-stats-grid">

        <div class="rpt-stat-card rpt-stat-blue">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="1.8">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
            </div>
            <div class="rpt-stat-value">{{ $s['total_bills'] }}</div>
            <div class="rpt-stat-label">Total Bills</div>
        </div>

        <div class="rpt-stat-card rpt-stat-indigo">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="1.8">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </div>
            <div class="rpt-stat-value">{{ number_format($s['total_meters'], 2) }} m</div>
            <div class="rpt-stat-label">Total Meters</div>
        </div>

        <div class="rpt-stat-card rpt-stat-green">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="1.8">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"></path>
                </svg>
            </div>
            <div class="rpt-stat-value">₹ {{ number_format($s['total_amount'], 2) }}</div>
            <div class="rpt-stat-label">Amt. (excl. GST)</div>
        </div>

        <div class="rpt-stat-card rpt-stat-amber">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.8">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <div class="rpt-stat-value">₹ {{ number_format($s['total_cgst'], 2) }}</div>
            <div class="rpt-stat-label">CGST (2.5%)</div>
        </div>

        <div class="rpt-stat-card rpt-stat-orange">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#ea580c" stroke-width="1.8">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <div class="rpt-stat-value">₹ {{ number_format($s['total_sgst'], 2) }}</div>
            <div class="rpt-stat-label">SGST (2.5%)</div>
        </div>

        <div class="rpt-stat-card rpt-stat-red">
            <div class="rpt-stat-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="1.8">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"></path>
                    <text x="12" y="16" text-anchor="middle" font-size="10" fill="#dc2626" stroke="none" font-weight="700">₹</text>
                </svg>
            </div>
            <div class="rpt-stat-value">₹ {{ number_format($s['grand_total'], 2) }}</div>
            <div class="rpt-stat-label">Grand Total</div>
        </div>

    </div>

    {{-- ── BILLS TABLE ── --}}
    <div class="rpt-panel">
        <div class="rpt-panel-head">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
            <h3>Bills — {{ $this->getPeriodLabel() }}</h3>
            @if($s['total_bills'] > 0)
                <span class="rpt-badge-count">{{ $s['total_bills'] }} record{{ $s['total_bills'] !== 1 ? 's' : '' }}</span>
            @endif
        </div>

        @if($this->bills->isEmpty())
            <div class="rpt-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <p>No bills found for {{ $this->getPeriodLabel() }}</p>
                <span>Try selecting a different month or year.</span>
            </div>
        @else
            <div class="rpt-table-wrap">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th style="width:36px">#</th>
                            <th>Bill No.</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Challan No.</th>
                            <th class="th-right">Meters</th>
                            <th class="th-right">Rate (₹)</th>
                            <th class="th-right">Amount (₹)</th>
                            <th class="th-right">CGST (₹)</th>
                            <th class="th-right">SGST (₹)</th>
                            <th class="th-right">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($this->bills as $i => $bill)
                            @php
                                $challan  = $bill->challan;
                                $customer = $challan?->customer;
                                $product  = $challan?->product;
                            @endphp
                            <tr>
                                <td class="td-center text-muted" style="font-size:11px;">{{ $i + 1 }}</td>
                                <td><span class="badge-bill">{{ $bill->bill_number }}</span></td>
                                <td style="white-space:nowrap;font-size:12px;color:#475569;">
                                    {{ $bill->created_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td>
                                    <div style="font-weight:600;color:#0f172a;">{{ $customer?->name ?? '—' }}</div>
                                    @if($customer?->gstin)
                                        <div class="sub-text">{{ $customer->gstin }}</div>
                                    @endif
                                </td>
                                <td style="color:#334155;">{{ $product?->name ?? '—' }}</td>
                                <td>
                                    @if($challan)
                                        <span class="badge-challan">{{ $challan->challan_number }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="td-num">{{ number_format($bill->total_meters, 2) }}</td>
                                <td class="td-num">{{ number_format($bill->rate, 2) }}</td>
                                <td class="td-num text-green">{{ number_format($bill->amount, 2) }}</td>
                                <td class="td-num text-amber">{{ number_format($bill->cgst_amount, 2) }}</td>
                                <td class="td-num text-orange">{{ number_format($bill->sgst_amount, 2) }}</td>
                                <td class="td-num text-blue font-bold">{{ number_format($bill->final_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="tf-label">Totals</td>
                            <td>{{ number_format($s['total_meters'], 2) }}</td>
                            <td></td>
                            <td class="text-green">{{ number_format($s['total_amount'], 2) }}</td>
                            <td class="text-amber">{{ number_format($s['total_cgst'], 2) }}</td>
                            <td class="text-orange">{{ number_format($s['total_sgst'], 2) }}</td>
                            <td class="text-blue">{{ number_format($s['grand_total'], 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>

    {{-- ── CUSTOMER BREAKDOWN ── --}}
    @if(!$this->bills->isEmpty())
    <div class="rpt-panel">
        <div class="rpt-panel-head">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 00-3-3.87"></path>
                <path d="M16 3.13a4 4 0 010 7.75"></path>
            </svg>
            <h3>Customer-wise Breakdown</h3>
            <span class="rpt-badge-count">{{ $this->customerBreakdown->count() }} customer{{ $this->customerBreakdown->count() !== 1 ? 's' : '' }}</span>
        </div>
        <div class="rpt-table-wrap">
            <table class="rpt-table">
                <thead>
                    <tr>
                        <th class="th-center" style="width:40px">Rank</th>
                        <th>Customer</th>
                        <th class="th-center">Bills</th>
                        <th class="th-right">Meters</th>
                        <th class="th-right">Amount (₹)</th>
                        <th class="th-right">GST (₹)</th>
                        <th class="th-right">Grand Total (₹)</th>
                        <th class="th-right" style="min-width:120px;">Revenue Share</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->customerBreakdown as $idx => $cust)
                        @php
                            $share = $s['grand_total'] > 0 ? ($cust['grand_total'] / $s['grand_total']) * 100 : 0;
                        @endphp
                        <tr>
                            <td class="td-center"><span class="cust-rank">{{ $idx + 1 }}</span></td>
                            <td style="font-weight:600;color:#0f172a;">{{ $cust['name'] }}</td>
                            <td class="td-center" style="font-weight:600;color:#3b82f6;">{{ $cust['count'] }}</td>
                            <td class="td-num">{{ number_format($cust['total_meters'], 2) }}</td>
                            <td class="td-num text-green">{{ number_format($cust['amount'], 2) }}</td>
                            <td class="td-num text-amber">{{ number_format($cust['gst'], 2) }}</td>
                            <td class="td-num text-blue font-bold">{{ number_format($cust['grand_total'], 2) }}</td>
                            <td>
                                <div class="rpt-progress-wrap">
                                    <div class="rpt-progress-bar">
                                        <div class="rpt-progress-fill" style="width:{{ number_format($share, 1) }}%"></div>
                                    </div>
                                    <span class="rpt-pct">{{ number_format($share, 1) }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="tf-label">Total</td>
                        <td class="td-center" style="font-weight:700;color:#1d4ed8;">{{ $s['total_bills'] }}</td>
                        <td>{{ number_format($s['total_meters'], 2) }}</td>
                        <td class="text-green">{{ number_format($s['total_amount'], 2) }}</td>
                        <td class="text-amber">{{ number_format($s['total_cgst'] + $s['total_sgst'], 2) }}</td>
                        <td class="text-blue">{{ number_format($s['grand_total'], 2) }}</td>
                        <td class="rpt-pct" style="text-align:right;margin-left:auto;">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

</div>
</x-filament-panels::page>
