<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quotation {{ $quotation->quotation_number }} - BWT</title>
    <style>
        @page { margin: 15mm 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1e293b;
            font-size: 9.5pt;
            line-height: 1.6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .logo-icon {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .brand {
            display: flex;
            flex-direction: column;
        }
        .company-name {
            font-size: 18pt;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .company-tagline {
            font-size: 7.5pt;
            color: #94a3b8;
            letter-spacing: 0.3px;
            margin-top: 2px;
        }
        .quotation-meta {
            text-align: right;
        }
        .quotation-label {
            font-size: 7pt;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .quotation-number {
            font-size: 13pt;
            font-weight: 700;
            color: #059669;
            margin-top: 2px;
        }
        .meta-date {
            font-size: 8pt;
            color: #64748b;
            margin-top: 4px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 7pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #fef3c7;
            color: #92400e;
            margin-top: 6px;
        }
        .divider {
            height: 3px;
            background: linear-gradient(to right, #059669, #10b981, transparent);
            margin-bottom: 24px;
            border-radius: 2px;
        }
        .client-info {
            margin-bottom: 24px;
            padding: 18px 20px;
            background: #f8fafc;
            border-radius: 10px;
            border-left: 4px solid #059669;
        }
        .client-info h3 {
            font-size: 10pt;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .client-info p {
            font-size: 8.5pt;
            color: #475569;
            line-height: 1.7;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        thead th {
            background: #0f172a;
            color: white;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
        }
        thead th:last-child { text-align: right; }
        thead th:nth-child(4),
        thead th:nth-child(5) { text-align: right; }
        tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 8.5pt;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody td:last-child { text-align: right; font-weight: 600; color: #059669; }
        tbody td:nth-child(4),
        tbody td:nth-child(5) { text-align: right; }
        tbody tr:nth-child(even) { background: #fafbfc; }
        .product-image {
            width: 42px;
            height: 42px;
            background: #f1f5f9;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
        }
        .product-code {
            font-family: 'SF Mono', 'Fira Code', monospace;
            font-size: 7pt;
            color: #059669;
            font-weight: 500;
        }
        .product-name {
            font-weight: 600;
            color: #0f172a;
            font-size: 9pt;
        }
        .product-specs {
            font-size: 7pt;
            color: #94a3b8;
        }
        .totals {
            margin-left: auto;
            width: 280px;
            background: #f8fafc;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }
        .totals table { margin-bottom: 0; }
        .totals td {
            padding: 5px 0;
            border: none;
            font-size: 9pt;
        }
        .totals td:last-child { text-align: right; }
        .totals .subtotal td { color: #475569; }
        .totals .subtotal td:last-child { font-weight: 500; }
        .totals .margin-row td { color: #059669; }
        .totals .margin-row td:last-child { font-weight: 600; }
        .totals .grand-total td {
            font-size: 12pt;
            font-weight: 800;
            color: #0f172a;
            padding-top: 10px;
        }
        .totals .grand-total td:last-child { color: #059669; }
        .terms {
            padding: 18px 20px;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 24px;
        }
        .terms h4 {
            font-size: 8pt;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 10px;
        }
        .terms p {
            font-size: 7.5pt;
            color: #64748b;
            line-height: 1.8;
        }
        .footer {
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 7pt;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-area">
            <div class="logo-icon">
                @php $settings = app(\App\Settings\GeneralSettings::class); @endphp
                <img src="{{ $settings->logo_path ? public_path($settings->logo_path) : public_path('images/bwt-logo.jpg') }}" alt="BWT">
            </div>
            <div class="brand">
                <div class="company-name">BWT</div>
                <div class="company-tagline">Premium B2B Wholesale Attachments</div>
            </div>
        </div>
        <div class="quotation-meta">
            <div class="quotation-label">Quotation</div>
            <div class="quotation-number">{{ $quotation->quotation_number }}</div>
            <div class="meta-date">Date: {{ $quotation->created_at->format('F d, Y') }}</div>
            <div><span class="status-badge">{{ $quotation->status }}</span></div>
        </div>
    </div>

    <div class="divider"></div>

    @php $meta = $quotation->user?->userMeta?->metadata ?? []; @endphp
    <div class="client-info">
        <h3>{{ $meta['wholesale_company_name'] ?? $quotation->user?->name ?? 'Client Name' }}</h3>
        <p>
            {{ $meta['address'] ?? 'Client Address' }}<br>
            {{ $meta['city'] ?? '' }}{{ $meta['city'] ? ', ' : '' }}{{ $meta['postal_code'] ?? '' }}<br>
            Attn: {{ $quotation->user?->name ?? 'Contact Person' }} &middot; {{ $quotation->user?->email ?? 'email@example.com' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 46px;">&nbsp;</th>
                <th>Product</th>
                <th>Specifications</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotation->items as $item)
            @php $lineTotal = $item->price * $item->quantity; @endphp
            <tr>
                <td>
                    <div class="product-image">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </td>
                <td>
                    <div class="product-name">{{ $item->product?->product_description ?? $item->product?->product_title ?? 'Product' }}</div>
                    <div class="product-code">{{ $item->product?->product_code }}</div>
                </td>
                <td>
                    <div class="product-specs">
                        {{ $item->product?->weight ? $item->product->weight . ' kg' : '' }}{{ $item->product?->width ? ' &middot; ' . $item->product->width . ' mm' : '' }}{{ $item->product?->machine_class ? ' &middot; ' . $item->product->machine_class : '' }}
                    </div>
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($item->price, 2) }}</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($lineTotal, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 24px; color: #94a3b8;">No items in this quotation</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $subtotal = $quotation->items->sum(fn($i) => $i->price * $i->quantity);
        $marginAmount = $subtotal * ($quotation->margin_percentage / 100);
        $grandTotal = $subtotal + $marginAmount;
    @endphp
    <div class="totals">
        <table>
            <tr class="subtotal">
                <td>Subtotal</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($subtotal, 2) }}</td>
            </tr>
            @if($quotation->margin_percentage > 0)
            <tr class="margin-row">
                <td>Margin ({{ $quotation->margin_percentage }}%)</td>
                <td>+{{ config('app.currency_symbol') }}{{ number_format($marginAmount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>Grand Total</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($grandTotal, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <h4>Terms &amp; Conditions</h4>
        <p>
            1. All prices are in USD and exclusive of applicable taxes.<br>
            2. Payment terms: 30 days net from invoice date.<br>
            3. Quotation valid for 14 days from the date of issue.<br>
            4. Lead times are estimated and subject to confirmation at time of order.<br>
            5. All products are covered by manufacturer warranty per separate warranty document.<br>
            6. Prices are based on selling price, not DDP.
        </p>
    </div>

    <div class="footer">
        <div>BWT &copy; {{ date('Y') }} &middot; Premium B2B Wholesale Attachments</div>
        <div>Page 1 of 1</div>
        <div>Generated {{ date('Y-m-d H:i') }}</div>
    </div>
</body>
</html>
