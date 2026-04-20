<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Challan - {{ $challan->challan_number }}</title>
    @php
        $owner        = $challan->owner;
        $biz          = $challan->businessDetail ?? $owner?->businessDetails()->first() ?? null;

        $bizName        = $biz?->business_name          ?? $owner?->business_name    ?? 'GURUDEV TEXTILES';
        $bizAddress     = $biz?->business_address       ?? $owner?->business_address ?? 'Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank, SURAT.';
        $bizMobile      = $biz?->mobile                 ?? $owner?->mobile           ?? '98790 69490';
        $bizGstin       = $biz?->gstin                  ?? $owner?->gstin            ?? '24EZAPP5247K1Z3';
        $bizMfgDealers  = $biz?->manufacturers_dealers_in ?? 'ART SILK CLOTH';

        // Split business name into first word + rest for two-tone display
        $nameParts    = explode(' ', trim($bizName), 2);
        $nameFirst    = $nameParts[0];
        $nameRest     = $nameParts[1] ?? '';
    @endphp
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #0f172a;
            background: #fff;
            padding: 5px;
        }
        .main-wrapper {
            border: 2px solid #1e3a8a;
            width: 100%;
            margin: 0 auto;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            position: relative;
            padding: 10px 15px 10px;
            background: #f8fafc;
            border-bottom: 2px solid #1e3a8a;
        }
        .challan-tag {
            background-color: #1e3a8a;
            color: #fff;
            padding: 3px 12px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            display: inline-block;
            letter-spacing: 1px;
            border-radius: 4px;
        }
        .mo-number {
            position: absolute;
            right: 15px;
            top: 10px;
            font-weight: bold;
            font-size: 12px;
            color: #1e3a8a;
        }
        .mfg-box {
            position: absolute;
            right: 15px;
            top: 28px;
            border: 2px solid #1e3a8a;
            color: #dc2626;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border-radius: 6px;
            line-height: 1.3;
            background: #fff;
        }
        .mfg-box span { display: block; font-size: 11px; color: #1e3a8a; }
        .brand-title { margin-top: 4px; margin-bottom: 2px; }
        .brand-gurudev {
            font-family: "Georgia", serif;
            font-size: 36px;
            color: #dc2626;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .brand-textiles {
            font-family: "Georgia", serif;
            font-size: 24px;
            color: #1e3a8a;
            font-weight: bold;
            margin-left: 10px;
            letter-spacing: 1px;
        }

        /* ── ADDRESS BAR ── */
        .address-bar {
            text-align: center;
            font-size: 11px;
            color: #1e3a8a;
            background: #f1f5f9;
            border-bottom: 2px solid #1e3a8a;
            padding: 5px 10px;
            font-weight: bold;
        }

        /* ── INFO PANEL ── */
        table { border-collapse: collapse; width: 100%; }

        .info-table td {
            border: 1px solid #1e3a8a;
            padding: 5px 10px;
            vertical-align: top;
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .info-table td:first-child { border-left: none; }
        .info-table td:last-child  { border-right: none; }
        .info-table td span.val {
            font-weight: normal;
            color: #0f172a;
            font-size: 13px;
            margin-left: 5px;
        }
        .info-table td span.val-large {
            font-weight: bold;
            color: #1e3a8a;
            font-size: 16px;
            text-transform: uppercase;
            margin-left: 5px;
        }
        .info-table td span.val-highlight {
            font-weight: bold;
            color: #ea580c;
            font-size: 17px;
            margin-left: 6px;
        }

        /* ── GRID TABLE ── */
        .grid-wrap { width: 100%; border-collapse: collapse; }

        .grid-section { width: 69%; vertical-align: top; }
        .summary-section { width: 31%; vertical-align: top; border-left: 2px solid #1e3a8a; }

        .grid-table { width: 100%; border-collapse: collapse; }
        .grid-table th {
            background: #1e3a8a;
            color: #fff;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 4px;
            border-right: 1px solid #3b5ba3;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid-table th:last-child { border-right: none; }
        .grid-table td {
            text-align: center;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            height: 28px;
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
            color: #0f172a;
        }
        .grid-table td.row-num {
            background: #f1f5f9;
            font-weight: bold;
            color: #475569;
            font-size: 11px;
            font-family: inherit;
            border-right: 2px solid #cbd5e1;
            width: 7%;
        }
        .grid-table tr:nth-child(even) td { background: #fafbff; }
        .grid-table tr:nth-child(even) td.row-num { background: #eef2f9; }
        .grid-table .total-row td {
            background: #1e3a8a !important;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
            height: 36px;
            border-right: 1px solid #3b5ba3;
            border-bottom: none;
        }
        .grid-table .total-row td.row-lbl {
            font-size: 11px;
            letter-spacing: 0.5px;
            font-family: inherit;
        }

        /* ── SUMMARY PANEL ── */
        .summary-inner { padding: 16px 14px; }

        .totals-box {
            border: 2px solid #1e3a8a;
            border-radius: 6px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .totals-box-header {
            background: #1e3a8a;
            color: #fff;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            padding: 5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .totals-box-body { padding: 10px 12px; background: #f8fafc; }
        .totals-row { margin-bottom: 12px; }
        .totals-row:last-child { margin-bottom: 0; }
        .totals-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .totals-value {
            font-size: 26px;
            font-weight: bold;
            color: #1e3a8a;
            text-align: right;
            border-bottom: 2px solid #cbd5e1;
            padding-bottom: 3px;
            line-height: 1.1;
            min-height: 34px;
        }

        .no-dyeing {
            text-align: center;
            color: #ef4444;
            font-weight: bold;
            font-size: 13px;
            padding: 10px 8px;
            border: 1px dashed #ef4444;
            border-radius: 6px;
            background: #fef2f2;
            letter-spacing: 0.5px;
            line-height: 1.4;
        }

        /* ── FOOTER ── */
        .footer-bar {
            background: #f8fafc;
            border-top: 2px solid #1e3a8a;
        }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-table td {
            padding: 10px 15px;
            vertical-align: bottom;
            border: none;
        }
        .footer-left  { width: 50%; border-right: 1px solid #cbd5e1; }
        .footer-right { width: 50%; text-align: right; }

        .nb-text {
            font-size: 10px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 8px;
        }
        .nb-text strong { color: #1e3a8a; }

        .sign-line {
            display: inline-block;
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            min-width: 160px;
            text-align: center;
            margin-top: 30px;
        }

        .recv-note {
            font-size: 10px;
            color: #475569;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .auth-title {
            font-weight: bold;
            color: #dc2626;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
<div class="main-wrapper">

    {{-- ═══ HEADER ═══ --}}
    <div class="header">
        <div class="challan-tag">Delivery Challan</div>
        <div class="mo-number">Mo.: {{ $bizMobile }}</div>

        <div class="mfg-box">
            Manufacturer &amp; Dealers in :<br>
            <span>{{ $bizMfgDealers }}</span>
        </div>

        <div class="brand-title">
            <span class="brand-gurudev">{{ $nameFirst }}</span>
            @if($nameRest)
            <span class="brand-textiles">{{ $nameRest }}</span>
            @endif
        </div>
    </div>

    {{-- ═══ ADDRESS BAR ═══ --}}
    <div class="address-bar">
        {{ $bizAddress }}
    </div>

    {{-- ═══ INFO PANEL ═══ --}}
    <table class="info-table" style="border-top: none; border-bottom: 2px solid #1e3a8a;">
        <tr>
            <td style="width: 65%; border-top: none; padding: 5px 10px; background: #f8fafc;">
                <span style="color:#64748b;">GSTIN No.:</span>
                <span style="color:#dc2626; font-family:'Courier New',monospace; font-size:15px; margin-left:5px; font-weight:bold;">{{ $bizGstin }}</span>
            </td>
            <td style="width: 35%; border-top: none; padding: 5px 10px; background: #f8fafc;">
                <span style="color:#64748b;">Quality / Product :</span>
                <span class="val" style="font-weight:bold; color:#1e3a8a; font-size:14px;">{{ $challan->product?->name ?: 'N/A' }}</span>
            </td>
        </tr>
    </table>

    <table class="info-table" style="border-top: none; border-bottom: 2px solid #1e3a8a;">
        <tr>
            <td rowspan="4" style="width: 65%; padding: 0; background: #fff; border-top: none;">
                <div style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1;">
                    <span style="color:#64748b;">Name :</span>
                    <span class="val-large">{{ $challan->customer?->name ?: 'N/A' }}</span>
                </div>
                <div style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1; min-height: 38px;">
                    <span style="color:#64748b;">Address :</span>
                    <span class="val" style="font-size:12px; text-transform:uppercase;">{{ $challan->customer?->address ?: 'N/A' }}</span>
                </div>
                <div style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1;">
                    <span style="color:#64748b;">GSTIN :</span>
                    <span class="val" style="font-size:14px; font-weight:bold; letter-spacing:1px;">{{ $challan->customer?->GSTIN ?: '—' }}</span>
                </div>
                <div style="padding: 6px 10px;">
                    <span style="color:#64748b;">Mobile :</span>
                    <span class="val" style="font-size:14px; font-weight:bold;">{{ $challan->customer?->mobile_number ?: '—' }}</span>
                </div>
            </td>
            <td style="width: 35%; padding: 6px 10px; border-bottom: 1px solid #cbd5e1; border-top: none; background:#fff;">
                <span style="color:#64748b;">Challan No.</span>
                <span class="val-highlight">#{{ $challan->challan_number }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1; background:#fff;">
                <span style="color:#64748b;">Date :</span>
                <span class="val" style="font-size:14px; font-weight:bold;">{{ \Carbon\Carbon::parse($challan->date)->format('d M, Y') }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1; background:#fff;">
                <span style="color:#64748b;">Broker :</span>
                <span class="val" style="font-size:14px; font-weight:bold;">{{ $challan->broker ?: '—' }}</span>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px 10px; background:#f8fafc;">
                <span style="color:#64748b;">Total Pieces :</span>
                <span class="val" style="font-size:16px; font-weight:bold; color:#1e3a8a;">{{ $challan->total_pieces > 0 ? $challan->total_pieces : '—' }}</span>
            </td>
        </tr>
    </table>

    {{-- ═══ GRID + SUMMARY ═══ --}}
    @php
        $grid = [];
        foreach ($challan->items as $item) {
            $grid[$item->row_no][$item->column_no] = $item->meters;
        }
        $colTotals = array_fill(1, 6, 0);
        foreach ($grid as $r => $cols) {
            foreach ($cols as $c => $val) {
                if (isset($colTotals[$c])) $colTotals[$c] += floatval($val);
            }
        }
    @endphp

    <table class="grid-wrap">
        <tr>
            {{-- Left: measurement grid --}}
            <td class="grid-section">
                <table class="grid-table">
                    <thead>
                        <tr>
                            <th style="width:7%;">No.</th>
                            @for($c = 1; $c <= 6; $c++)
                            <th style="width:15.5%;">Thaan {{ $c }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @for($r = 1; $r <= 12; $r++)
                        <tr>
                            <td class="row-num">{{ $r }}</td>
                            @for($c = 1; $c <= 6; $c++)
                                @php $val = $grid[$r][$c] ?? 0; @endphp
                                <td>{{ floatval($val) > 0 ? number_format(floatval($val), 2) : '' }}</td>
                            @endfor
                        </tr>
                        @endfor
                        <tr class="total-row">
                            <td class="row-lbl">TOTAL</td>
                            @for($c = 1; $c <= 6; $c++)
                            <td>{{ $colTotals[$c] > 0 ? number_format($colTotals[$c], 2) : '' }}</td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </td>

            {{-- Right: summary panel --}}
            <td class="summary-section">
                <div class="summary-inner">
                    <div class="totals-box">
                        <div class="totals-box-header">Summary</div>
                        <div class="totals-box-body">
                            <div class="totals-row">
                                <div class="totals-label">Total Pieces</div>
                                <div class="totals-value">{{ $challan->total_pieces > 0 ? $challan->total_pieces : '' }}</div>
                            </div>
                            <div class="totals-row">
                                <div class="totals-label">Total Meters</div>
                                <div class="totals-value">{{ $challan->total_meters > 0 ? number_format($challan->total_meters, 2) : '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="no-dyeing">
                        &#9733; NO DYEING GUARANTEE &#9733;
                    </div>
                </div>
            </td>
        </tr>
    </table>

    {{-- ═══ FOOTER ═══ --}}
    <div class="footer-bar">
        <table class="footer-table">
            <tr>
                <td class="footer-left">
                    <div class="nb-text">
                        <strong>N.B. :</strong> Report to be presented within 24 hours.<br>
                        After that, it will not be entertained.
                    </div>
                    <div class="sign-line">Prepared By</div>
                </td>
                <td class="footer-right">
                    <div class="recv-note">Received the above goods in good and sound condition.</div>
                    <div class="auth-title">FOR, {{ strtoupper($bizName) }}</div>
                    <div class="sign-line">Receiver's Signature</div>
                </td>
            </tr>
        </table>
    </div>

</div>
</body>
</html>
