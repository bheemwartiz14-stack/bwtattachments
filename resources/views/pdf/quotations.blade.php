<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quotation {{ $quotation->number ?? 'Q-001' }} - Attachment Portal</title>
    <style>
        @page { margin: 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1e293b;
            font-size: 10pt;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            border-bottom: 2px solid #059669;
            margin-bottom: 24px;
        }
        .logo-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-icon {
            width: 40px;
            height: 40px;
            background: #059669;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }
        .company-name {
            font-size: 16pt;
            font-weight: 700;
            color: #0f172a;
        }
        .company-tagline {
            font-size: 8pt;
            color: #64748b;
        }
        .quotation-meta {
            text-align: right;
        }
        .quotation-number {
            font-size: 14pt;
            font-weight: 700;
            color: #059669;
        }
        .quotation-label {
            font-size: 8pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .client-info {
            margin-bottom: 24px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .client-info h3 {
            font-size: 10pt;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .client-info p {
            font-size: 9pt;
            color: #475569;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        thead th {
            background: #059669;
            color: white;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
        }
        thead th:first-child { border-radius: 6px 0 0 0; }
        thead th:last-child { border-radius: 0 6px 0 0; text-align: right; }
        thead th:nth-child(4) { text-align: right; }
        thead th:nth-child(5) { text-align: right; }
        tbody td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9pt;
        }
        tbody td:last-child { text-align: right; font-weight: 600; }
        tbody td:nth-child(4) { text-align: right; }
        tbody td:nth-child(5) { text-align: right; }
        tbody tr:hover { background: #f8fafc; }
        .product-image {
            width: 48px;
            height: 48px;
            background: #f1f5f9;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 16px;
        }
        .product-code {
            font-family: monospace;
            font-size: 8pt;
            color: #059669;
            font-weight: 500;
        }
        .product-name {
            font-weight: 600;
            color: #0f172a;
        }
        .product-specs {
            font-size: 7.5pt;
            color: #64748b;
        }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals table { margin-bottom: 0; }
        .totals td {
            padding: 6px 12px;
            border: none;
            font-size: 9pt;
        }
        .totals td:last-child { text-align: right; }
        .totals .subtotal td { color: #475569; }
        .totals .grand-total td {
            font-size: 12pt;
            font-weight: 700;
            color: #0f172a;
            border-top: 2px solid #0f172a;
            padding-top: 8px;
        }
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #94a3b8;
        }
        .terms {
            margin-top: 24px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .terms h4 {
            font-size: 9pt;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }
        .terms p {
            font-size: 8pt;
            color: #475569;
            line-height: 1.6;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 7pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-area">
            <div class="logo-icon">AP</div>
            <div>
                <div class="company-name">Attachment Portal</div>
                <div class="company-tagline">Premium B2B Wholesale Attachments</div>
            </div>
        </div>
        <div class="quotation-meta">
            <div class="quotation-label">Quotation</div>
            <div class="quotation-number">{{ $quotation->number ?? 'Q-001' }}</div>
            <div style="font-size: 8pt; color: #64748b; margin-top: 4px;">Date: {{ $quotation->date ?? date('F d, Y') }}</div>
            <div style="margin-top: 4px;"><span class="status-badge">{{ $quotation->status ?? 'Draft' }}</span></div>
        </div>
    </div>

    <div class="client-info">
        <h3>{{ $quotation->client->name ?? $quotation->client_name ?? 'Client Name' }}</h3>
        <p>
            {{ $quotation->client->address ?? $quotation->client_address ?? 'Client Address' }}<br>
            {{ $quotation->client->city ?? '' }}{{ $quotation->client->city ? ', ' : '' }}{{ $quotation->client->state ?? '' }}{{ $quotation->client->zip ?? '' }}<br>
            Attn: {{ $quotation->client->contact_person ?? $quotation->client_contact ?? 'Contact Person' }} &middot; {{ $quotation->client->email ?? $quotation->client_email ?? 'email@example.com' }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">&nbsp;</th>
                <th>Product</th>
                <th>Specifications</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($quotation->items ?? $quotation->products ?? []) as $item)
            <tr>
                <td>
                    <div class="product-image">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </td>
                <td>
                    <div class="product-name">{{ $item->product->description ?? $item->description ?? $item->name ?? 'Product Name' }}</div>
                    <div class="product-code">{{ $item->product->product_code ?? $item->product_code ?? $item->code ?? 'CODE' }}</div>
                </td>
                <td>
                    <div class="product-specs">
                        {{ $item->weight ?? '' }}{{ $item->weight ? ' kg' : '' }}{{ $item->width ? ' &middot; ' . $item->width . ' mm' : '' }}{{ $item->machine_class ?? $item->machineClass ? ' &middot; ' . ($item->machine_class ?? $item->machineClass) : '' }}
                    </div>
                </td>
                <td>{{ $item->quantity ?? $item->qty ?? 1 }}</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($item->unit_price ?? $item->price ?? 0, 2) }}</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format(($item->unit_price ?? $item->price ?? 0) * ($item->quantity ?? $item->qty ?? 1), 2) }}</td>
            </tr>
            @empty
            <tr>
                <td>
                    <div class="product-image">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </td>
                <td>
                    <div class="product-name">Bucket GP-12 Series</div>
                    <div class="product-code">BKT-GP-12</div>
                </td>
                <td><div class="product-specs">1,200 kg &middot; 1,800 mm &middot; 10-15T</div></td>
                <td>2</td>
                <td>$4,500.00</td>
                <td>$9,000.00</td>
            </tr>
            <tr>
                <td>
                    <div class="product-image">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </td>
                <td>
                    <div class="product-name">Ripper Tooth RT-4</div>
                    <div class="product-code">RIP-RT-4</div>
                </td>
                <td><div class="product-specs">350 kg &middot; 4 teeth &middot; 15-20T</div></td>
                <td>3</td>
                <td>$1,800.00</td>
                <td>$5,400.00</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr class="subtotal">
                <td>Subtotal</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($quotation->subtotal ?? ($quotation->total ?? 14400), 2) }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total</td>
                <td>{{ config('app.currency_symbol') }}{{ number_format($quotation->total ?? ($quotation->grand_total ?? 14400), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="terms">
        <h4>Terms &amp; Conditions</h4>
        <p>
            {!! nl2br(e($quotation->terms ?? $quotation->terms_and_conditions ?? "1. All prices are in USD and exclusive of applicable taxes.\n2. Payment terms: 30 days net from invoice date.\n3. Quotation valid for 14 days from the date of issue.\n4. Lead times are estimated and subject to confirmation at time of order.\n5. All products are covered by manufacturer warranty per separate warranty document.\n6. Prices are based on selling price, not DDP.")) !!}
        </p>
    </div>

    <div class="footer">
        <div>Attachment Portal &copy; {{ date('Y') }}</div>
        <div>Page 1 of 1</div>
        <div>Generated on {{ date('Y-m-d H:i') }}</div>
    </div>
</body>
</html>
