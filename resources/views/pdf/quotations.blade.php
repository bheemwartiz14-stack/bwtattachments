<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }} - BWT</title>
    <style>
        @page { margin: 0; size: A4; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            margin: 0; background: #efefef;
            font-family: Arial, Helvetica, sans-serif;
            color: #333; font-size: 12px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page { width: 210mm; min-height: 297mm; margin: auto; background: #fff; position: relative; }
        .header { background: #005691; color: #fff; padding: 35px 45px; }
        .header-table { width: 100%; }
        .header-table td { vertical-align: top; }
        .logo { font-size: 32px; font-weight: bold; border: 3px solid #fff; display: inline-block; padding: 10px 30px; letter-spacing: 2px; }
        .logo img { max-height: 50px; vertical-align: middle; }
        .company-tag { margin-top: 8px; font-size: 12px; opacity: .9; letter-spacing: 1px; }
        .company-info { text-align: right; font-size: 12px; line-height: 22px; }
        .company-info a { color: #fff; text-decoration: none; }
        .title { padding: 35px 45px 20px; }
        .title table { width: 100%; }
        .title h1 { margin: 0; color: #005691; font-size: 34px; }
        .badge {
            background: #0BA360; color: #fff; padding: 8px 18px;
            border-radius: 20px; font-size: 12px; font-weight: bold;
            display: inline-block;
        }
        .badge.draft { background: #f59e0b; }
        .cards { padding: 0 45px; }
        .cards table { width: 100%; border-spacing: 15px; margin-left: -15px; }
        .card {
            background: #F7F9FC; border: 1px solid #E5EAF0;
            border-radius: 8px; padding: 18px;
        }
        .card-title { color: #005691; font-size: 13px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; }
        .card p { margin: 4px 0; line-height: 1.5; }
        .product-section { padding: 30px 45px; }
        .product-table { width: 100%; border-collapse: collapse; }
        .product-table thead { background: #005691; color: #fff; }
        .product-table th { padding: 14px; text-align: left; font-size: 12px; }
        .product-table td { padding: 14px; border-bottom: 1px solid #ECECEC; font-size: 12px; vertical-align: top; }
        .product-table tbody tr:nth-child(even) { background: #FAFAFA; }
        .product-table .text-right { text-align: right; }
        .product-table .text-center { text-align: center; }
        .product-specs { font-size: 11px; color: #666; margin-top: 4px; }
        .summary { padding: 0 45px; overflow: hidden; }
        .summary-table { width: 320px; float: right; border-collapse: collapse; }
        .summary-table td { padding: 10px; }
        .summary-table tr:last-child { background: #005691; color: #fff; font-size: 15px; font-weight: bold; }
        .terms { clear: both; padding: 40px 45px 20px; }
        .terms h3 { color: #005691; margin-bottom: 12px; }
        .terms ul { padding-left: 18px; margin: 0; }
        .terms li { margin-bottom: 8px; line-height: 1.5; }
        .footer {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: #F5F7FA; padding: 25px 45px;
        }
        .signature { width: 100%; }
        .signature td { width: 50%; }
        .line { width: 180px; border-top: 1px solid #555; margin-top: 60px; }
        .line-right { width: 180px; border-top: 1px solid #555; margin-top: 60px; margin-left: auto; }
        .footer-text { text-align: center; margin-top: 20px; color: #888; font-size: 11px; }
    </style>
</head>
<body>
    @php
        $settings = app(\App\Settings\GeneralSettings::class);
        $reseller = $quotation->reseller;
        $resellerMeta = $reseller?->userMeta?->metadata ?? [];
        $subtotal = $quotation->items->sum(fn($i) => $i->price * $i->quantity);
        $marginAmount = $subtotal * ($quotation->margin_percentage / 100);
        $grandTotal = $subtotal + $marginAmount;
    @endphp

    <div class="page">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="logo">
                            @if($settings->logo_path)
                                <img src="{{ public_path($settings->logo_path) }}" alt="BWT">
                            @else
                                BWT
                            @endif
                        </div>
                        <div class="company-tag">{{ $settings->site_title }}</div>
                    </td>
                    <td class="company-info">
                        <strong>{{ $settings->site_title }}</strong><br>
                        {{ $settings->address_line_1 }}, {{ $settings->city }}, {{ $settings->country }}<br>
                        <a href="mailto:{{ $settings->support_email }}">{{ $settings->support_email }}</a><br>
                        {{ $settings->support_phone }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="title">
            <table>
                <tr>
                    <td>
                        <h1>QUOTATION</h1>
                        Quotation No: <strong>{{ $quotation->quotation_number }}</strong>
                    </td>
                    <td align="right">
                        <span class="badge {{ $quotation->status === 'draft' ? 'draft' : '' }}">{{ ucfirst($quotation->status) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="cards">
            <table>
                <tr>
                    <td width="50%">
                        <div class="card">
                            <div class="card-title">Bill To</div>
                            <p><strong>{{ $resellerMeta['wholesale_company_name'] ?? $reseller?->name ?? 'Client' }}</strong></p>
                            <p>{{ $quotation->contact_name ?? $reseller?->name ?? '' }}</p>
                            <p>{{ $resellerMeta['address'] ?? '' }}</p>
                            <p>{{ $resellerMeta['city'] ?? '' }}{{ ($resellerMeta['city'] ?? '') && ($resellerMeta['postal_code'] ?? '') ? ', ' : '' }}{{ $resellerMeta['postal_code'] ?? '' }}</p>
                            <p>{{ $quotation->contact_email ?? $reseller?->email ?? '' }}</p>
                            <p>{{ $quotation->contact_phone ?? '' }}</p>
                        </div>
                    </td>
                    <td width="50%">
                        <div class="card">
                            <div class="card-title">Quotation Details</div>
                            <p>Date : {{ $quotation->issue_date?->format('d M Y') ?? $quotation->created_at->format('d M Y') }}</p>
                            <p>Valid Until : {{ $quotation->valid_until?->format('d M Y') ?? 'N/A' }}</p>
                            <p>Currency : {{ config('app.currency_symbol') ?? 'EUR' }}</p>
                            @if($quotation->margin_percentage > 0)
                            <p>Margin : {{ $quotation->margin_percentage }}%</p>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="product-section">
            <table class="product-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>Product</th>
                        <th style="width: 80px; text-align: center;">Qty</th>
                        <th style="width: 100px; text-align: right;">Unit Price</th>
                        <th style="width: 120px; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quotation->items as $index => $item)
                    @php $lineTotal = $item->price * $item->quantity; @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product?->product_code ?? 'SKU' }}</strong>
                            <div>{{ $item->product?->product_description ?? $item->product?->product_title ?? 'Product' }}</div>
                            <div class="product-specs">
                                {{ $item->product?->machine_class ? 'Machine: ' . $item->product->machine_class : '' }}{{ $item->product?->weight ? ' | Weight: ' . $item->product->weight . ' kg' : '' }}{{ $item->product?->width ? ' | Width: ' . $item->product->width . ' mm' : '' }}
                            </div>
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ config('app.currency_symbol') }}{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 24px; color: #999;">No items</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="summary">
            <table class="summary-table">
                <tr>
                    <td>Subtotal</td>
                    <td align="right">{{ config('app.currency_symbol') }}{{ number_format($subtotal, 2) }}</td>
                </tr>
                @if($quotation->margin_percentage > 0)
                <tr>
                    <td>Margin ({{ $quotation->margin_percentage }}%)</td>
                    <td align="right">{{ config('app.currency_symbol') }}{{ number_format($marginAmount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Grand Total</td>
                    <td align="right">{{ config('app.currency_symbol') }}{{ number_format($grandTotal, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="terms">
            <h3>Terms & Conditions</h3>
            <ul>
                @if($quotation->notes)
                <li>{{ $quotation->notes }}</li>
                @endif
                <li>Quotation valid for 30 days from date of issue.</li>
                <li>Delivery within 25–35 working days after order confirmation.</li>
                <li>Payment: 30% Deposit, 70% Before Shipment.</li>
                <li>Warranty: 12 Months from date of delivery.</li>
                <li>Prices exclude customs duties and local taxes.</li>
            </ul>
        </div>

        <div class="footer">
            <table class="signature">
                <tr>
                    <td>
                        <div class="line"></div>
                        Authorized Signature
                    </td>
                    <td align="right">
                        <div class="line-right"></div>
                        Customer Signature
                    </td>
                </tr>
            </table>
            <div class="footer-text">
                Thank you for your business.<br>
                {{ $settings->site_title }} | {{ $settings->support_email }} | {{ $settings->support_phone }}
            </div>
        </div>
    </div>
</body>
</html>
