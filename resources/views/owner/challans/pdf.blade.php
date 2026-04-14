<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $challan->challan_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .container { max-width: 700px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0f766e; padding-bottom: 12px; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px;}
        .shop-name { font-size: 20px; font-weight: bold; color: #0f766e; }
        .shop-details { font-size: 9px; color: #64748b; margin-top: 3px; }
        .bill-number { font-size: 16px; font-weight: bold; }
        .customer-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; margin-bottom: 15px; }
        .customer-box .label { font-size: 9px; text-transform: uppercase; color: #94a3b8; font-weight: bold; letter-spacing: 0.5px; }
        .customer-box .name { font-size: 13px; font-weight: bold; margin-top: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.items th { background: #f1f5f9; border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; }
        table.items td { border: 1px solid #e2e8f0; padding: 6px 8px; font-size: 10px; }
        table.items td.right, table.items th.right { text-align: right; }
        .footer { clear: both; margin-top: 40px; padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; font-size: 9px; color: #94a3b8; }
        .signature { text-align: right; margin-top: 60px; }
        .signature .line { border-top: 1px solid #1e293b; width: 150px; display: inline-block; margin-bottom: 3px; }
    </style>
</head>
<body>
<div class="container">
    <div class="title">DELIVERY CHALLAN</div>
    <table style="width:100%;margin-bottom:15px;border-bottom:2px solid #0f766e;padding-bottom:12px;">
        <tr>
            <td style="vertical-align:top">
                @if($challan->shop?->logo_path)
                    <img src="{{ public_path('storage/' . $challan->shop->logo_path) }}" style="height:40px;margin-bottom:5px;" alt="Logo">
                @endif
                <div class="shop-name">{{ $challan->shop?->name ?? 'Textile Shop' }}</div>
                <div class="shop-details">
                    @if($challan->shop?->address){{ $challan->shop->address }}, @endif
                    {{ $challan->shop?->city }} {{ $challan->shop?->state }}<br>
                    @if($challan->shop?->gstin)GSTIN: {{ $challan->shop->gstin }}@endif
                </div>
            </td>
            <td style="text-align:right;vertical-align:top">
                <div class="bill-number">{{ $challan->challan_number }}</div>
                <div style="font-size:10px;color:#64748b;margin-top:3px;">
                    Date: {{ $challan->challan_date?->format('d/m/Y') }}
                </div>
            </td>
        </tr>
    </table>

    <div class="customer-box">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Party Name</div>
                    <div class="name">{{ $challan->party_name }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Broker Name</div>
                    <div class="name" style="font-size: 11px; font-weight: normal;">{{ $challan->broker_name ?? '—' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead><tr>
            <th style="width:30px">#</th><th>Product</th><th class="right" style="width:50px">Pcs</th><th class="right" style="width:60px">Meters</th><th class="right" style="width:60px">Weight</th>
        </tr></thead>
        <tbody>
            @foreach($challan->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="right">{{ $item->pieces ?: '-' }}</td>
                    <td class="right">{{ $item->meters ?: '-' }}</td>
                    <td class="right">{{ $item->weight ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="line"></div><br>
        <span style="font-size:10px">Receiver's Signature</span>
    </div>

    <div class="footer">
        <span>Generated on {{ now()->format('d/m/Y h:i A') }}</span>
    </div>
</div>
</body>
</html>
