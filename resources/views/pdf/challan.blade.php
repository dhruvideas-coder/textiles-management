<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Challan - {{ $challan->challan_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 13px; 
            color: #1e293b; 
            background: #fff; 
            padding: 20px; 
        }
        .main-wrapper {
            border: 2px solid #1e3a8a;
            width: 100%;
            margin: 0 auto;
        }
        .header { 
            text-align: center; 
            position: relative; 
            padding: 20px 20px;
            background-color: #f8fafc;
            border-bottom: 2px solid #1e3a8a;
        }
        .header-tag {
            background-color: #1e3a8a;
            color: #fff;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            display: inline-block;
            position: absolute;
            left: 15px;
            top: 15px;
            letter-spacing: 1px;
        }
        .header-mo {
            position: absolute;
            right: 15px;
            top: 15px;
            font-weight: bold;
            font-size: 14px;
            color: #475569;
        }
        .header-title {
            font-family: "Georgia", "Times New Roman", serif;
            font-size: 38px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin-top: 15px;
            color: #1e3a8a;
        }
        .header-subtitle-wrapper { margin-top: 10px; }
        .header-subtitle {
            background-color: #1e3a8a;
            color: #fff;
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        .header-address {
            font-size: 13px;
            margin-top: 15px;
            color: #475569;
        }
        
        .info-panel { width: 100%; border-collapse: collapse; border-bottom: 2px solid #1e3a8a; }
        .info-left-panel { width: 65%; border-right: 2px solid #1e3a8a; vertical-align: top; background: #fff; }
        .info-right-panel { width: 35%; vertical-align: top; background: #f8fafc; }
        
        .info-label { font-size: 11px; color: #64748b; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 5px; }
        .info-val-large { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; display: block; }
        .info-val-normal { font-size: 15px; font-weight: bold; color: #1e293b; display: block; line-height: 1.4; }
        .info-val-highlight { font-size: 22px; font-weight: bold; color: #ea580c; display: block; white-space: nowrap; }
        
        .right-info-table { width: 100%; border-collapse: collapse; }
        .right-info-cell { padding: 16px 20px; border-bottom: 1px solid #cbd5e1; }
        .right-info-cell:last-child { border-bottom: none; }
        
        .grid-container { width: 100%; border-collapse: collapse; }
        
        .summary-wrapper {
            padding: 20px;
        }
        
        .totals-box {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8fafc;
        }
        .totals-row { margin-bottom: 18px; }
        .totals-row:last-child { margin-bottom: 0; }
        .totals-label { font-size: 12px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .totals-value { font-size: 26px; font-weight: bold; color: #1e3a8a; text-align: right; border-bottom: 2px solid #cbd5e1; padding-bottom: 4px; min-height: 38px; }
        
        .guarantee-note {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #ef4444;
            padding: 12px;
            border: 1px dashed #ef4444;
            border-radius: 6px;
            background: #fef2f2;
            margin-top: 40px;
            letter-spacing: 0.5px;
        }
        
        .footer-note {
            margin-top: 25px;
            display: table;
            width: 100%;
            font-size: 13px;
            color: #475569;
        }
        .footer-note-left { display: table-cell; width: 50%; vertical-align: bottom; padding-right: 20px; line-height: 1.5; }
        .footer-note-right { display: table-cell; width: 50%; vertical-align: bottom; text-align: right; }
        
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #94a3b8;
            width: 200px;
            display: inline-block;
            padding-top: 8px;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
        }
        .signature-line-left {
            margin-top: 50px;
            border-top: 1px solid #94a3b8;
            width: 200px;
            display: block;
            padding-top: 8px;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="main-wrapper">
        <div class="header">
            <span class="header-tag">Delivery Challan</span>
            <span class="header-mo">Mo.: 98790 69490</span>
            
            <div class="header-title">GURUDEV TEXTILES</div>
            
            <div class="header-subtitle-wrapper">
                <span class="header-subtitle">Manufacturer &amp; Dealers in : ART SILK CLOTH</span>
            </div>
            
            <div class="header-address">
                Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank, SURAT.
            </div>
        </div>
        
        <table class="info-panel">
            <tr>
                <td class="info-left-panel" style="padding: 20px;">
                    <div style="margin-bottom: 20px;">
                        <span class="info-label">Customer Name</span>
                        <span class="info-val-large">{{ $challan->customer?->name ?: 'N/A' }}</span>
                    </div>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 50%; vertical-align: top; padding-right: 15px; border-right: 1px solid #e2e8f0;">
                                <span class="info-label">Address</span>
                                <span class="info-val-normal" style="color: #475569;">{{ $challan->customer?->address ?: 'N/A' }}</span>
                            </td>
                            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                                <span class="info-label">Contact Details</span>
                                @if($challan->customer?->GSTIN)
                                <div style="margin-bottom: 6px;"><strong style="color:#64748b; font-size:12px;">GSTIN:</strong> <span style="font-size:14px; font-weight: bold; color: #0f172a;">{{ $challan->customer?->GSTIN }}</span></div>
                                @endif
                                @if($challan->customer?->mobile_number)
                                <div><strong style="color:#64748b; font-size:12px;">Mo:</strong> <span style="font-size:14px; font-weight: bold; color: #0f172a;">{{ $challan->customer?->mobile_number }}</span></div>
                                @endif
                                @if(!$challan->customer?->GSTIN && !$challan->customer?->mobile_number)
                                <span style="font-size:14px; color:#94a3b8; font-weight:bold;">N/A</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <div style="margin-top: 20px; border-top: 1px dashed #cbd5e1; padding-top: 15px;">
                        <span class="info-label">Quality / Product</span>
                        <span class="info-val-normal" style="color: #1e3a8a; font-size: 18px;">{{ $challan->product?->name ?: 'N/A' }}</span>
                    </div>
                </td>
                
                <td class="info-right-panel" style="padding: 0;">
                    <table class="right-info-table">
                        <tr>
                            <td class="right-info-cell" style="padding-top: 20px;">
                                <span class="info-label">Challan No.</span>
                                <span class="info-val-highlight">#{{ $challan->challan_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="right-info-cell">
                                <span class="info-label">Date</span>
                                <span class="info-val-normal" style="font-size: 17px;">{{ \Carbon\Carbon::parse($challan->date)->format('d M, Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="right-info-cell">
                                <span class="info-label">Broker Name</span>
                                <span class="info-val-normal" style="font-size: 16px;">{{ $challan->broker ?: 'N/A' }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="right-info-cell" style="background-color: #f1f5f9; padding-bottom: 20px;">
                                <span class="info-label">Our GSTIN No.</span>
                                <span class="info-val-normal" style="color: #1e3a8a; font-family: monospace; font-size: 16px; letter-spacing: 1px;">24EZAPP5247K1Z3</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table class="grid-container">
            @php
                $grid = [];
                foreach($challan->items as $item) {
                    $grid[$item->row_no][$item->column_no] = $item->meters;
                }
                $colTotals = array_fill(1, 6, 0);
            @endphp
            @for($r = 1; $r <= 12; $r++)
            <tr>
                <td style="width: 6%; text-align: center; border-right: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; background: #f8fafc; font-weight: bold; color: #64748b; height: 32px;">{{ $r }}</td>
                @for($c = 1; $c <= 6; $c++)
                    @php
                        $val = $grid[$r][$c] ?? 0;
                        $colTotals[$c] += floatval($val);
                    @endphp
                <td style="width: 10.5%; text-align: center; border-right: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1;">
                    <span class="val-text" style="font-family: 'Courier New', Courier, monospace;">{{ floatval($val) > 0 ? number_format(floatval($val), 2) : '' }}</span>
                </td>
                @endfor
                
                @if($r == 1)
                <!-- Right column summary pane -->
                <td rowspan="12" style="width: 31%; vertical-align: top; border-bottom: 1px solid #cbd5e1; border-left: 2px solid #1e3a8a;">
                    <div class="summary-wrapper">
                        <div class="totals-box">
                            <div class="totals-row">
                                <div class="totals-label">Total Pieces</div>
                                <div class="totals-value">
                                    {{ $challan->total_pieces > 0 ? $challan->total_pieces : '' }}
                                </div>
                            </div>
                            <div class="totals-row">
                                <div class="totals-label">Total Meters</div>
                                <div class="totals-value">
                                    {{ $challan->total_meters > 0 ? number_format($challan->total_meters, 2) : '' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="guarantee-note">
                            * NO DYEING GUARANTEE *
                        </div>
                    </div>
                </td>
                @endif
            </tr>
            @endfor
            
            <tr class="total-row">
                <td style="font-size: 11px; color: #1e3a8a; text-align: center; font-weight: bold; border-right: 1px solid #cbd5e1; height: 42px; background: #f1f5f9; letter-spacing: 0.5px;">TOTAL</td>
                @for($c = 1; $c <= 6; $c++)
                <td style="text-align: center; border-right: 1px solid #cbd5e1; background: #f1f5f9;">
                    <span class="val-text" style="color: #1e3a8a; font-family: 'Courier New', Courier, monospace;">{{ $colTotals[$c] > 0 ? number_format($colTotals[$c], 2) : '' }}</span>
                </td>
                @endfor
                <td style="background-color: #1e3a8a; color: #fff; text-align: center; padding: 4px 12px; font-weight: bold; font-size: 16px; letter-spacing: 1.5px; text-transform: uppercase;">
                    Total Summary
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        <div class="footer-note-left">
            <p><strong>N.B. :</strong> Report to be Presented within 24 Hours. <br>After that, it will not be entertained.</p>
            <div class="signature-line-left">Prepared By</div>
        </div>
        <div class="footer-note-right">
            <p style="font-weight: bold;">Received the above goods in good and sound condition.</p>
            <div class="signature-line">Receiver's Signature</div>
        </div>
    </div>

</body>
</html>
