<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bill - {{ $bill->bill_number }}</title>
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
            position: relative;
        }
        .header {
            text-align: center;
            position: relative;
            padding: 10px;
            background: #f8fafc;
            border-bottom: 2px solid #1e3a8a;
        }
        .tax-invoice-tag {
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
        .brand-title {
            margin-top: 5px;
            margin-bottom: 2px;
        }
        .brand-gurudev {
            font-family: "Georgia", serif;
            font-size: 36px;
            color: #dc2626; /* red */
            font-weight: bold;
            letter-spacing: 1px;
        }
        .brand-textiles {
            font-family: "Georgia", serif;
            font-size: 24px;
            color: #1e3a8a; /* blue */
            font-weight: bold;
            margin-left: 10px;
            letter-spacing: 1px;
        }
        .mfg-box {
            position: absolute;
            right: 15px;
            top: 30px;
            border: 2px solid #1e3a8a;
            color: #dc2626;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border-radius: 6px;
            line-height: 1.3;
            transform: rotate(-2deg);
            background: #fff;
        }
        .mfg-box span {
            display: block;
            font-size: 11px;
            color: #1e3a8a;
        }
        
        .address-bar {
            text-align: center;
            font-size: 11px;
            color: #1e3a8a;
            background: #f1f5f9;
            border-bottom: 2px solid #1e3a8a;
            padding: 5px;
            font-weight: bold;
        }
        
        table { border-collapse: collapse; width: 100%; }
        
        .info-table td {
            border: 1px solid #1e3a8a;
            padding: 4px 10px;
            vertical-align: top;
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .info-table td:first-child { border-left: none; }
        .info-table td:last-child { border-right: none; }
        
        .info-table td span.val {
            font-weight: normal;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 13px;
            margin-left: 5px;
        }
        
        .items-table th {
            border: 1px solid #1e3a8a;
            background-color: #1e3a8a;
            color: #fff;
            padding: 6px;
            font-size: 11px;
            text-align: center;
            text-transform: uppercase;
        }
        .items-table td {
            border: 1px solid #1e3a8a;
            padding: 6px;
            text-align: center;
            vertical-align: top;
            font-size: 13px;
        }
        .items-table th:first-child, .items-table td:first-child { border-left: none; }
        .items-table th:last-child, .items-table td:last-child { border-right: none; }
        
        .items-table td.desc {
            text-align: left;
            font-weight: bold;
            color: #1e3a8a;
        }
        .items-table td.val {
            font-family: inherit;
            color: #0f172a;
        }
        .tall-cell {
            height: 60px;
        }
        
        .bottom-table td {
            vertical-align: top;
            padding: 0;
            border-right: 1px solid #1e3a8a;
            border-bottom: 2px solid #1e3a8a;
        }
        .bottom-table td:last-child { border-right: none; }
        
        .goods-section {
            padding: 10px;
            border-bottom: 1px solid #cbd5e1;
            background: #f8fafc;
        }
        .goods-delivered {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .no-dyeing {
            text-align: center;
            color: #ef4444;
            font-weight: bold;
            font-size: 14px;
            padding: 8px;
            border-bottom: 1px solid #cbd5e1;
            letter-spacing: 1px;
            background: #fef2f2;
        }
        .amount-words {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            min-height: 40px;
            background: #f8fafc;
        }
        .bank-details {
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            line-height: 1.5;
        }
        .terms {
            font-size: 9px;
            color: #475569;
            padding: 8px 10px;
            line-height: 1.3;
        }
        .terms-title {
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
            font-size: 10px;
        }
        
        .tax-calc-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            border-right: none;
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .tax-calc-table td.val-col {
            border-left: 1px solid #cbd5e1;
            text-align: right;
            color: #0f172a;
            font-size: 14px;
        }
        .tax-calc-table tr.total-row td {
            background-color: #1e3a8a;
            color: #fff;
            padding: 10px;
            border-bottom: none;
        }
        .tax-calc-table tr.total-row td.val-col {
            background-color: #f1f5f9;
            color: #0f172a;
            font-size: 18px;
            border-bottom: none;
            border-left: 2px solid #1e3a8a;
        }
        
        .footer-signatures {
            width: 100%;
            background: #f8fafc;
        }
        .footer-signatures td {
            padding: 10px 15px;
            vertical-align: bottom;
            height: 70px;
            border: none;
        }
        .receiver-sign {
            width: 40%;
            font-size: 11px;
            font-weight: bold;
            color: #1e3a8a;
        }
        .auth-sign {
            width: 60%;
            text-align: right;
            font-size: 11px;
            color: #1e3a8a;
        }
        .auth-sign-title {
            font-weight: bold;
            color: #dc2626;
            margin-top: 10px;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .certify-text {
            font-size: 9px;
            text-align: right;
            display: block;
            margin-bottom: 15px;
            color: #475569;
        }
        .underline {
            border-bottom: 1px solid #1e3a8a;
            display: inline-block;
            min-width: 130px;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="header">
            <div class="tax-invoice-tag">TAX INVOICE</div>
            <div class="mo-number">Mo.: 98790 69490</div>
            
            <div class="mfg-box">
                Manufacturer & Dealers in :<br>
                <span>ART SILK CLOTH</span>
            </div>
            
            <div class="brand-title">
                <span class="brand-gurudev">GURUDEV</span>
                <span class="brand-textiles">TEXTILES</span>
            </div>
        </div>
        
        <div class="address-bar">
            Plot No. 38, Sonal Ind. Estate-3, G.H.B. Road, Behind Chickuwadi, Near New Water Tank, SURAT.
        </div>
        
        <table class="info-table" style="border-top: none; border-bottom: 2px solid #1e3a8a;">
            <tr>
                <td style="width: 65%; border-top: none; padding: 6px 10px; background: #f8fafc;">
                    <span style="color:#64748b;">GSTIN No.:</span> <span style="color:#dc2626; font-family: 'Courier New', monospace; font-size:15px; margin-left: 5px; font-weight: bold;">24EZAPP5247K1Z3</span>
                </td>
                <td style="width: 35%; border-top: none; padding: 6px 10px; background: #f8fafc;">
                    <span style="color:#64748b;">HSN Code :</span> 
                </td>
            </tr>
        </table>
        
        <table class="info-table" style="border-top: none;">
            <tr>
                <td rowspan="3" style="width: 65%; padding: 0; background: #fff;">
                    <div style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1;">
                        <span style="color:#64748b;">Name :</span> <span class="val" style="font-size: 16px; font-weight: bold; text-transform: uppercase; color: #1e3a8a;">{{ $bill->challan?->customer?->name }}</span>
                    </div>
                    <div style="padding: 6px 10px; border-bottom: 1px solid #cbd5e1; min-height: 40px;">
                        <span style="color:#64748b;">Address:</span> <span class="val" style="font-size: 12px; text-transform: uppercase;">{{ $bill->challan?->customer?->address }}</span>
                    </div>
                    <div style="padding: 6px 10px;">
                        <span style="color:#64748b;">GSTIN :</span> <span class="val" style="font-size: 14px; font-weight: bold; letter-spacing: 1px;">{{ $bill->challan?->customer?->GSTIN }}</span>
                    </div>
                </td>
                <td style="width: 35%; padding: 5px 10px; border-bottom: 1px solid #cbd5e1; background: #fff;">
                    <span style="color:#64748b;">Bill No.</span> <span class="val" style="font-weight: bold; margin-left: 10px; font-size: 15px; color: #ea580c;">#{{ $bill->bill_number }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 10px; border-bottom: 1px solid #cbd5e1; background: #fff;">
                    <span style="color:#64748b;">Date :</span> <span class="val">{{ \Carbon\Carbon::parse($bill->created_at)->format('d M, Y') }}</span>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 10px; border-bottom: 1px solid #cbd5e1; background: #fff;">
                    <span style="color:#64748b;">Challan No.</span> <span class="val" style="font-weight: bold;">{{ $bill->challan?->challan_number }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 6px 10px; border-top: 1px solid #1e3a8a; background: #f8fafc; border-bottom: none;">
                    <span style="color:#64748b;">Broker</span> <span class="val" style="margin-left: 10px; font-size: 14px;">{{ $bill->challan?->broker }}</span>
                </td>
            </tr>
        </table>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Sr. No.</th>
                    <th style="width: 40%;">Product Description</th>
                    <th style="width: 10%;">Total Pcs.</th>
                    <th style="width: 14%;">Total Meters</th>
                    <th style="width: 12%;">Rate</th>
                    <th style="width: 16%;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tall-cell">1</td>
                    <td class="tall-cell desc" style="font-size: 15px;">{{ $bill->challan?->product?->name }}</td>
                    <td class="tall-cell val">{{ $bill->challan?->total_pieces ?? '-' }}</td>
                    <td class="tall-cell val">{{ number_format($bill->total_meters, 2) }}</td>
                    <td class="tall-cell val">{{ number_format($bill->rate, 2) }}</td>
                    <td class="tall-cell val" style="font-weight: bold; color: #ea580c; font-size: 14px;">{{ number_format($bill->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <table class="bottom-table">
            <tr>
                <td style="width: 60%;">
                    <div class="goods-section">
                        <div class="goods-delivered">Goods Delivered to <span class="underline" style="width: 65%; border-color: #cbd5e1;"></span></div>
                        <div class="goods-delivered" style="margin-top: 15px;"><span class="underline" style="width: 100%; border-color: #cbd5e1;"></span></div>
                        <div class="goods-delivered" style="margin-top: 20px;">Due Date <span class="underline" style="width: 80%; border-color: #cbd5e1;"></span></div>
                    </div>
                    <div class="no-dyeing">
                        * NO DYEING GUARANTEE *
                    </div>
                    <div class="amount-words">
                        <span style="color:#64748b;">Amount in Words :</span><br>
                        <span style="font-weight: bold; color: #0f172a; font-size: 12px; text-transform: capitalize; display: block; margin-top: 2px;">{{ (new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT))->format($bill->final_total) }} rupees only</span>
                    </div>
                    <div class="bank-details">
                        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                            <tr><td style="width: 25%; padding: 1px 0; border: none; font-weight: bold; color: #1e3a8a;">Bank Name</td><td style="width: 5%; border: none;">:</td><td style="border: none;"></td></tr>
                            <tr><td style="padding: 1px 0; border: none; font-weight: bold; color: #1e3a8a;">A/c. No.</td><td style="border: none;">:</td><td style="border: none;"></td></tr>
                            <tr><td style="padding: 1px 0; border: none; font-weight: bold; color: #1e3a8a;">IFSC Code</td><td style="border: none;">:</td><td style="border: none;"></td></tr>
                        </table>
                    </div>
                    <div class="terms">
                        <div class="terms-title">TERMS & CONDITIONS :</div>
                        (1) Payment to be made by A/c. Payee's Cheque or Draft only. (2) Any Complaint for goods should be made within 5 days. (3) Interest at 24% per annum will be charged after due date. (4) We are not responsible for any loss or damage during transit. (5) Sold goods will not be taken back in any Condition. (6) Subject to Surat Jurisdiction only.
                    </div>
                </td>
                <td style="width: 40%;">
                    <table class="tax-calc-table">
                        <tr>
                            <td style="border-top: none;">Total Amount Before Tax</td>
                            <td class="val-col" style="border-top: none;">{{ number_format($bill->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Add : CGST &nbsp;&nbsp;&nbsp; <span style="color: #64748b;">2.5%</span></td>
                            <td class="val-col">{{ number_format($bill->cgst_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Add : SGST &nbsp;&nbsp;&nbsp; <span style="color: #64748b;">2.5%</span></td>
                            <td class="val-col">{{ number_format($bill->sgst_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="height: 40px; vertical-align: top;">Add : IGST &nbsp;&nbsp;&nbsp; <span style="color: #64748b;">0%</span></td>
                            <td class="val-col" style="height: 40px; vertical-align: top;">-</td>
                        </tr>
                        <tr class="total-row">
                            <td style="font-size: 13px; text-transform: uppercase;">Total Amount After Tax</td>
                            <td class="val-col" style="font-weight: bold; color: #1e3a8a;">Rs. {{ number_format($bill->final_total, 2) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        <table class="footer-signatures">
            <tr>
                <td class="receiver-sign">
                    <span style="color:#64748b;">Receiver Sign.</span> <br>
                    <span class="underline" style="min-width: 140px; margin-top: 25px; border-color: #94a3b8;"></span>
                </td>
                <td class="auth-sign">
                    <span class="certify-text">Certified that the particulars given above are true & correct.</span>
                    <div class="auth-sign-title">FOR, GURUDEV TEXTILES</div>
                    <div style="margin-top: 25px; color: #1e3a8a; font-weight: bold;">Authorised Signatory</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
