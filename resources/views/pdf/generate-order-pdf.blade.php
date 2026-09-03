@php
    // dd($order);
    $sender = $order->fromUser ?? ($order->user ?? null);
    $recipient = $order->toUser ?? null;
    $senderMeta = $sender?->userMeta?->metadata ?? [];
    $recipientMeta = $recipient?->userMeta?->metadata ?? [];
    $senderRole = $sender?->roles->first()?->name;
    $senderLogoBase64 = '';
    $senderLogoPath = match (strtolower($senderRole ?? '')) {
        'wholesaler' => $sender?->getFirstMediaPath('wholesale_client_logo'),
        'reseller'   => $sender?->getFirstMediaPath('retailer_client_logo'),
        'customer'   => $sender?->getFirstMediaPath('customer_logo'),
        default      => null,
    };
    if ($senderLogoPath && file_exists($senderLogoPath)) {
        $type = pathinfo($senderLogoPath, PATHINFO_EXTENSION) ?: 'png';
        $data = @file_get_contents($senderLogoPath);
        if ($data) {
            $senderLogoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }
    $recipientLogoPath =
        $recipient?->getFirstMediaPath('retailer_client_logo') ?:
        $recipient?->getFirstMediaPath('wholesale_client_logo');
    $recipientLogoBase64 = '';
    if ($recipientLogoPath && file_exists($recipientLogoPath)) {
        $type = pathinfo($recipientLogoPath, PATHINFO_EXTENSION) ?: 'png';
        $data = @file_get_contents($recipientLogoPath);
        if ($data) {
            $recipientLogoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }
    $topRightName = $sender?->name ?? 'Admin';
    $topRightStreet = $senderMeta['address'] ?? 'Street name';
    $topRightCity = trim(($senderMeta['postal_code'] ?? '1234AB') . ' ' . ($senderMeta['city'] ?? 'Place'));
    $topRightCountry = $senderMeta['country'] ?? 'Country';
    $topRightPhone = $sender->phone ?? ($senderMeta['phone'] ?? '+31620315250');
    $topRightEmail = $sender->email ?? 'admin@bwt.com';
    // Safe raw values bypass decimal: cast issue with comma
    $rawSub = $order->getAttributes()['sub_total'] ?? ($order->attributesToArray()['sub_total'] ?? 0);
    $rawVatAmt = $order->getAttributes()['vat_amount'] ?? ($order->getAttributes()['tax_amount'] ?? 0);
    $rawGrand = $order->getAttributes()['grand_total'] ?? 0;
    $rawVatPerc = $order->getAttributes()['vat_percentage'] ?? 0;
    $subTotal = (float) str_replace([','], '', (string) $rawSub);
    $taxAmount = (float) str_replace([','], '', (string) $rawVatAmt);
    $grandTotal = (float) str_replace([','], '', (string) $rawGrand);
    $vatPerc = (string) str_replace([','], '', (string) $rawVatPerc);
    $currency = config('app.currency_symbol', '€');
    $show_pdf = $order->show_logo_on_pdf;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->order_number }}</title>
</head>

<body
    style="margin:0;padding:0;background:#fff;color:#000;font-family:Helvetica,Arial,sans-serif;font-size:9pt;line-height:1.4;">
    <div style="width:100%;padding:18px 28px 18px 28px;">
        <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:72%;vertical-align:top;padding-right:10px;">
                    @if ($show_pdf)
                        <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding:4px 8px;vertical-align:top;">
                                    <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="width:50%;vertical-align:middle;text-align:left;height:46px;">
                                                @if ($senderLogoBase64)
                                                    <img src="{{ $senderLogoBase64 }}"
                                                        style="height:42px;width:auto;max-width:190px;object-fit:contain;" />
                                                @else
                                                    <div style="height:42px;line-height:42px;font-size:7pt;color:#999;text-align:center;">Wholesaler logo</div>
                                                @endif
                                            </td>
                                            {{-- <td style="width:50%;vertical-align:middle;text-align:left;height:46px;">
                                                @if ($recipientLogoBase64)
                                                    <img src="{{ $recipientLogoBase64 }}"
                                                        style="height:42px;width:auto;max-width:190px;object-fit:contain;" />
                                                @else
                                                    <div style="height:42px;line-height:42px;font-size:7pt;color:#999;text-align:center;">Customer logo</div>
                                                @endif
                                            </td> --}}
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    @endif
                </td>
                <td style="width:28%;vertical-align:top;text-align:right;padding-left:10px;">
                    <div style="font-size:9pt;font-weight:bold;color:#000;line-height:1.35;">
                        {{ $sender?->name ?? 'Admin' }}</div>
                    <div style="font-size:8.5pt;color:#000;line-height:1.35;">
                        {{ $senderMeta['address'] ?? 'Street name' }}</div>
                    <div style="font-size:8.5pt;color:#000;line-height:1.35;">
                        {{ trim(($senderMeta['postal_code'] ?? '1234AB') . ' ' . ($senderMeta['city'] ?? 'Place')) }}
                    </div>
                    <div style="font-size:8.5pt;color:#000;line-height:1.35;">{{ $senderMeta['country'] ?? 'Country' }}
                    </div>
                    <div style="font-size:8.5pt;color:#000;line-height:1.35;margin-top:4px;">T:
                        {{ $sender?->phone ?? 'Admin' }}
                    </div>
                    <div style="font-size:8.5pt;color:#000;line-height:1.35;">E: {{ $sender?->email ?? 'Admin' }}</div>
                </td>
            </tr>
        </table>
        <div
            style="margin-top:18px;margin-bottom:12px;font-size:20pt;font-weight:bold;color:#111;letter-spacing:0.3px;">
            ORDER</div>
        <table style="width:100%;border-collapse:collapse;border:1px solid #000;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:50%;border-right:1px solid #000;padding:5px 8px;font-size:8.5pt;background:#fff;"><span
                        style="font-weight:bold;">Order No.:</span> {{ $order->order_number }}</td>
                <td style="width:50%;padding:5px 8px;font-size:8.5pt;background:#fff;"><span
                        style="font-weight:bold;">Order date:</span>
                    {{ $order->order_date?->format('d M Y') ?? $order->created_at->format('d M Y') }}</td>
            </tr>
        </table>
        <table style="width:100%;border-collapse:collapse;border:1px solid #000;margin-top:8px;" cellpadding="0"
            cellspacing="0">
            <tr>
                <td colspan="2"
                    style="background:#404040;color:#fff;font-weight:bold;padding:5px 8px;font-size:9pt;">Order to:</td>
            </tr>
            <tr>
                <td
                    style="width:55%;vertical-align:top;padding:7px 8px;font-size:8.5pt;line-height:1.45;border-right:1px solid #000;">
                    <div style="font-weight:bold;">{{ $recipient?->name ?? 'Admin' }}</div>
                    <div>{{ $recipientMeta['address'] ?? '' }}</div>
                    <div>{{ $recipientMeta['postal_code'] ?? '' }} {{ $recipientMeta['city'] ?? '' }}</div>
                    <div>{{ $recipientMeta['country'] ?? '' }}</div>
                </td>
                <td style="width:45%;vertical-align:top;padding:7px 8px;font-size:8.5pt;line-height:1.45;">
                    <div>Tel. : {{ $recipient?->phone ?? '-' }}</div>
                    <div>Email : {{ $recipient->email ?? ($recipientMeta['email'] ?? 'Email') }}</div>
                    @php $vat = $recipientMeta['vat_number'] ?? ''; @endphp
                    <div style="margin-top:4px;">VAT: {{ $vat }}</div>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse:collapse;border:1px solid #000;margin-top:8px;" cellpadding="0"
            cellspacing="0">
            <thead>
                <tr>
                    <th
                        style="background:#404040;color:#fff;font-size:7.5pt;font-weight:bold;padding:5px 6px;text-align:left;border-right:1px solid #000;width:16%;">
                        Product code</th>
                    <th
                        style="background:#404040;color:#fff;font-size:7.5pt;font-weight:bold;padding:5px 6px;text-align:left;border-right:1px solid #000;width:34%;">
                        Product name</th>
                    <th
                        style="background:#404040;color:#fff;font-size:7.5pt;font-weight:bold;padding:5px 6px;text-align:right;border-right:1px solid #000;width:16%;">
                        Unit price</th>
                    <th
                        style="background:#404040;color:#fff;font-size:7.5pt;font-weight:bold;padding:5px 6px;text-align:center;border-right:1px solid #000;width:7%;">
                        Qty</th>
                    <th
                        style="background:#404040;color:#fff;font-size:7.5pt;font-weight:bold;padding:5px 6px;text-align:right;width:27%;">
                        Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    @php
                        $p = (float) str_replace([','], '', (string) $item->getAttributes()['price'] ?? $item->price);
                        $total = $p * (int) $item->quantity;
                    @endphp
                    <tr>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;border-right:1px solid #000;border-bottom:1px solid #000;">
                            {{ $item->product->product_code ?? '' }}</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;border-right:1px solid #000;border-bottom:1px solid #000;">
                            {{ $item->product->product_title ?? '' }}</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;text-align:right;border-right:1px solid #000;border-bottom:1px solid #000;white-space:nowrap;">
                            {{ $currency }} {{ number_format($p, 2, '.', ',') }}</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">
                            {{ $item->quantity }}</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;white-space:nowrap;">
                            {{ $currency }} {{ number_format($total, 2, '.', ',') }}</td>
                    </tr>
                @endforeach
                @for ($i = count($order->items); $i < 15; $i++)
                    <tr>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;border-right:1px solid #000;border-bottom:1px solid #000;height:14px;">
                            &nbsp;</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;border-right:1px solid #000;border-bottom:1px solid #000;">
                            &nbsp;</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;text-align:right;border-right:1px solid #000;border-bottom:1px solid #000;">
                            &nbsp;</td>
                        <td
                            style="padding:5px 6px;font-size:7.5pt;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">
                            &nbsp;</td>
                        <td style="padding:5px 6px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;">
                            &nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <table style="width:100%;border-collapse:collapse;margin-top:0;" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:62%;vertical-align:top;padding-top:6px;">
                    <div style="font-size:7.5pt;line-height:1.4;">{!! $order->notes ?? '' !!}</div>
                </td>
                <td style="width:38%;vertical-align:top;">
                    <table style="width:100%;border-collapse:collapse;border:1px solid #000;margin-top:0;"
                        cellpadding="0" cellspacing="0">
                        <tr>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;border-right:1px solid #000;">
                                Sub total:</td>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;white-space:nowrap;">
                                {{ $currency }} {{ number_format($subTotal, 2, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;border-right:1px solid #000;">
                                VAT {{ $vatPerc }}%:</td>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;border-bottom:1px solid #000;white-space:nowrap;">
                                {{ $currency }} {{ number_format($taxAmount, 2, '.', ',') }}</td>
                        </tr>
                        <tr>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;font-weight:bold;border-right:1px solid #000;">
                                Grand total:</td>
                            <td
                                style="padding:4px 8px;font-size:7.5pt;text-align:right;font-weight:bold;white-space:nowrap;">
                                {{ $currency }} {{ number_format($grandTotal, 2, '.', ',') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
