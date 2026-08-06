@php
    $quotationOwner = $quotation->user;

    $roleName = $quotationOwner?->roles->pluck('name')->first();

    $sender = match ($roleName) {
        'Wholesaler' => $quotationOwner,
        'Reseller' => $quotationOwner?->parent,
        default => null,
    };
    $senderLogoPath = $sender?->getFirstMediaPath('wholesale_client_logo');

    $senderMeta = $sender?->userMeta?->metadata ?? [];
    $defaultLogoPath = public_path('images/BIG.jpg');
    $logoPath = $senderLogoPath ?: $defaultLogoPath;
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_get_contents($logoPath);
    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $senderName = $senderMeta['wholesale_company_name'] ?? ($sender->name ?? '');
    $senderAddressParts = array_filter([
        $senderMeta['address'] ?? null,
        $senderMeta['city'] ?? null,
        $senderMeta['country'] ?? null,
    ]);
    $senderAddress = implode(', ', $senderAddressParts);
    $senderPhone = $senderMeta['phone'] ?? ($sender->phone ?? '');
    $senderEmail = $sender->email ?? '';

    $reseller = $quotation->reseller ?? null;
    $meta = $reseller?->userMeta?->metadata ?? [];

    $custAddressParts = array_filter([$meta['address'] ?? null, $meta['city'] ?? null, $meta['country'] ?? null]);
    $custAddress = implode(', ', $custAddressParts);
    $subTotal = (float) str_replace(',', '', $quotation->sub_total);
    $taxAmount = (float) str_replace(',', '', $quotation->tax_amount);
    $grandTotal = (float) str_replace(',', '', $quotation->grand_total);
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 32px;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
            background: #eef1f5;
            font-size: 13px;
            line-height: 1.5;
        }

        .sheet {
            width: 100%;
            max-width: 1160px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
        }

        .content {
            padding: 36px 44px 40px;
        }

        /* ===== Header ===== */
        .header {
            width: 100%;
            border-collapse: collapse;
        }

        .header .company {
            width: 70%;
            vertical-align: top;
        }

        .header .company .name {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            letter-spacing: -0.2px;
        }

        .header .company .contact {
            font-size: 13px;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.6;
        }

        .header .brand {
            width: 30%;
            text-align: right;
            vertical-align: top;
        }

        .header .brand img {
            max-width: 100%;
            max-height: 88px;
            height: auto;
        }

        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 24px 0;
        }

        /* ===== Title ===== */
        .hero {
            text-align: center;
            padding: 10px 0 26px;
        }

        .hero .eyebrow {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 600;
        }

        .hero h1 {
            font-size: 40px;
            font-weight: 800;
            margin: 6px 0 0;
            color: #111827;
            letter-spacing: -1px;
        }

        .hero .number {
            display: inline-block;
            margin-top: 12px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 13px;
            font-weight: 600;
            border-radius: 999px;
            padding: 6px 18px;
        }

        /* ===== Quote meta bar ===== */
        .metabar {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            background: #f9fafb;
            border: 1px solid #eef0f4;
            margin-bottom: 22px;
        }

        .metabar td {
            padding: 14px 20px;
            width: 33.33%;
            vertical-align: top;
        }

        .metabar .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            font-weight: 600;
        }

        .metabar .value {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-top: 4px;
        }

        /* ===== Section cards ===== */
        .card {
            border: 1px solid #eef0f4;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 22px;
        }

        .card .card-head {
            background: #f9fafb;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
            border-bottom: 1px solid #eef0f4;
        }

        .card .card-body {
            padding: 20px;
        }

        /* ===== Two column ===== */
        .two-col {
            width: 100%;
            border-collapse: collapse;
        }

        .two-col td {
            vertical-align: top;
            padding: 0;
        }

        .two-col .left {
            width: 62%;
            padding-right: 24px;
        }

        .two-col .right {
            width: 38%;
        }

        /* ===== Items table ===== */
        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items thead th {
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 700;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .items thead th.num,
        .items thead th.total {
            text-align: right;
        }

        .items thead th.qty {
            text-align: center;
        }

        .items tbody td {
            padding: 13px 16px;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        .items tbody tr:last-child td {
            border-bottom: none;
        }

        .items .code {
            color: #64748b;
            font-weight: 600;
        }

        .items .product {
            color: #111827;
            font-weight: 600;
        }

        .items .num,
        .items .total {
            text-align: right;
            white-space: nowrap;
        }

        .items .qty {
            text-align: center;
        }

        /* ===== Totals ===== */
        .totals {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #fbfcfd;
        }

        .totals td {
            padding: 11px 18px;
            font-size: 13px;
            border-bottom: 1px solid #eef0f4;
        }

        .totals tr:last-child td {
            border-bottom: none;
        }

        .totals .label {
            color: #6b7280;
            text-align: right;
        }

        .totals .amount {
            text-align: right;
            font-weight: 600;
            color: #1f2937;
            white-space: nowrap;
        }

        .totals .grand-row td {
            background: #f8fafc;
        }

        .totals .grand-label {
            font-weight: 700;
            color: #111827;
            font-size: 15px;
            text-align: right;
        }

        .totals .grand-amount {
            font-weight: 800;
            color: #0f172a;
            font-size: 18px;
            text-align: right;
            white-space: nowrap;
        }

        /* ===== Notes ===== */
        .notes {
            font-size: 12.5px;
            color: #4b5563;
            line-height: 1.7;
        }

        .notes h1,
        .notes h2,
        .notes h3 {
            color: #111827;
            margin: 10px 0 6px;
        }

        .notes p {
            margin: 6px 0;
        }

        .notes ul,
        .notes ol {
            margin: 6px 0;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <div class="sheet">
        <div class="content">

            <!-- ===== Header ===== -->
            <table class="header">
                <tr>
                    <td class="company">
                        <div class="name">{{ $senderName }}</div>
                        <div class="contact">
                            @if ($senderAddress)
                                <div>{{ $senderAddress }}</div>
                            @endif
                            @if ($senderPhone)
                                <div>{{ $senderPhone }}</div>
                            @endif
                            @if ($senderEmail)
                                <div>{{ $senderEmail }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="brand">
                        <img src="{{ $logoBase64 }}">
                    </td>
                </tr>
            </table>

            <!-- ===== Hero ===== -->
            <div class="hero">
                <div class="eyebrow">Official Document</div>
                <h1>Quotation</h1>
                <span class="number">{{ $quotation->quotation_number }}</span>
            </div>

            <div class="divider"></div>

            <!-- ===== Meta bar ===== -->
            <table class="metabar">
                <tr>
                    <td>
                        <div class="label">Quote No.</div>
                        <div class="value">{{ $quotation->quotation_number }}</div>
                    </td>
                    <td>
                        <div class="label">Quote Date</div>
                        <div class="value">{{ $quotation->created_at->format('d M Y') }}</div>
                    </td>
                    <td>
                        <div class="label">Valid Until</div>
                        <div class="value">{{ $quotation->valid_until?->format('d M Y') ?? '—' }}</div>
                    </td>
                </tr>
            </table>

            <!-- ===== Customer ===== -->
            <div class="card">
                <div class="card-head">Quotation To</div>
                <div class="card-body">
                    <table class="two-col">
                        <tr>
                            <td class="left">
                                <div style="font-size:16px;font-weight:700;color:#111827;">{{ $reseller->name ?? '' }}</div>
                                @if ($custAddress)
                                    <div style="color:#6b7280;margin-top:4px;">{{ $custAddress }}</div>
                                @endif
                                @if ($reseller?->email)
                                    <div style="color:#6b7280;">{{ $reseller->email }}</div>
                                @endif
                            </td>
                            <td class="right">
                                @if (!empty($meta['vat_number']))
                                    <div style="margin-bottom:6px;">
                                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;font-weight:600;display:block;">VAT Number</span>
                                        <span style="font-weight:600;color:#1f2937;">{{ $meta['vat_number'] }}</span>
                                    </div>
                                @endif
                                @if (!empty($reseller?->phone))
                                    <div style="margin-bottom:6px;">
                                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;font-weight:600;display:block;">Phone</span>
                                        <span style="font-weight:600;color:#1f2937;">{{ $reseller->phone }}</span>
                                    </div>
                                @endif
                                @if (!empty($meta['contact_name']))
                                    <div>
                                        <span style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;font-weight:600;display:block;">Contact</span>
                                        <span style="font-weight:600;color:#1f2937;">{{ $meta['contact_name'] }}</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ===== Items ===== -->
            <div class="card">
                <div class="card-head">Items</div>
                <div class="card-body" style="padding:0;">
                    <table class="items">
                        <thead>
                            <tr>
                                <th style="width:15%;">Code</th>
                                <th style="width:40%;">Product</th>
                                <th class="num" style="width:15%;">Unit Price</th>
                                <th class="qty" style="width:10%;">Qty</th>
                                <th class="total" style="width:20%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotation->items as $item)
                                @php
                                    $itemPrice = (float) str_replace(',', '', $item->price);
                                @endphp
                                <tr>
                                    <td class="code">{{ $item->product->product_code }}</td>
                                    <td class="product">{{ $item->product->product_title }}</td>
                                    <td class="num">{{ config('app.currency_symbol') }} {{ number_format($itemPrice, 2, '.', ',') }}</td>
                                    <td class="qty">{{ $item->quantity }}</td>
                                    <td class="total">{{ config('app.currency_symbol') }} {{ number_format($itemPrice * $item->quantity, 2, '.', ',') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===== Bottom: notes + totals ===== -->
            <table class="two-col">
                <tr>
                    <td class="left">
                        @if ($quotation->notes)
                            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#6b7280;margin-bottom:8px;">Notes</div>
                            <div class="notes">{!! $quotation->notes !!}</div>
                        @endif
                    </td>
                    <td class="right">
                        <table class="totals">
                            <tr>
                                <td class="label">Subtotal</td>
                                <td class="amount">{{ config('app.currency_symbol') }} {{ number_format($subTotal, 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td class="label">VAT {{ $quotation->vat_percentage }}%</td>
                                <td class="amount">{{ config('app.currency_symbol') }} {{ number_format($taxAmount, 2, '.', ',') }}</td>
                            </tr>
                            <tr class="grand-row">
                                <td class="grand-label">Grand Total</td>
                                <td class="grand-amount">{{ config('app.currency_symbol') }} {{ number_format($grandTotal, 2, '.', ',') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
