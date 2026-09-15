@php
    $sender = $order->fromUser ?? ($order->user ?? null);
    $recipient = $order->toUser ?? null;
    $senderMeta = $sender?->userMeta?->metadata ?? [];
    $recipientMeta = $recipient?->userMeta?->metadata ?? [];
    $senderRole = $sender?->roles->first()?->name;
    $senderLogoPath = match (strtolower($senderRole ?? '')) {
        'wholesaler' => $sender?->getFirstMediaPath('wholesale_client_logo', 'original'),
        'reseller' => $sender?->getFirstMediaPath('retailer_client_logo', 'large'),
        'customer' => $sender?->getFirstMediaPath('customer_logo', 'large'),
        default => null,
    };
    //  Convert Sender Logo to Base64
    $senderLogoBase64 = '';
    if ($senderLogoPath && file_exists($senderLogoPath)) {
        $extension = strtolower(pathinfo($senderLogoPath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'image/png';
        $data = @file_get_contents($senderLogoPath);
        if ($data !== false) {
            $senderLogoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($data);
        }
    }
    // Recipient Logo
    $recipientLogoPath =
        $recipient?->getFirstMediaPath('retailer_client_logo') ?:
        $recipient?->getFirstMediaPath('wholesale_client_logo');
    $recipientLogoBase64 = '';

    if ($recipientLogoPath && file_exists($recipientLogoPath)) {
        $extension = strtolower(pathinfo($recipientLogoPath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'image/png';
        $data = @file_get_contents($recipientLogoPath);
        if ($data !== false) {
            $recipientLogoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($data);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Sender Details
    |--------------------------------------------------------------------------
    */
    $topRightName = $sender?->name ?? 'Admin';
    $topRightStreet = $senderMeta['address'] ?? 'Street name';
    $topRightCity = trim(($senderMeta['postal_code'] ?? '1234AB') . ' ' . ($senderMeta['city'] ?? 'Place'));
    $topRightCountry = $senderMeta['country'] ?? 'Country';
    $topRightPhone = $sender?->phone ?? ($senderMeta['phone'] ?? '+31620315250');
    $topRightEmail = $sender?->email ?? 'admin@bwt.com';

    /*
    |--------------------------------------------------------------------------
    | Order Amounts
    |--------------------------------------------------------------------------
    */
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order {{ $order->order_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 13mm;
        }
    </style>
</head>

<body
    style="
        margin:0;
        padding:0;
        background:#fff;
        color:#000;
        font-family:Helvetica,Arial,sans-serif;
        font-size:9pt;
        line-height:1.4;
    ">

    <div style=" width:100%; margin:0; padding:0; "> <!-- ========================================================= -->
        <!-- TOP SECTION: LOGOS + RECIPIENT CONTACT -->
        <!-- ========================================================= -->
        <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
            <tr>

                <!-- LEFT SIDE -->
                <td
                    style="
                    width:72%;
                    vertical-align:top;
                    padding-right:10px;
                ">

                    <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
                        <tr>

                            <td
                                style="
                                padding:4px 8px;
                                vertical-align:top;
                            ">

                                <table style="width:100%;border-collapse:collapse;" cellpadding="0" cellspacing="0">
                                    <tr>

                                        <!-- SENDER LOGO -->
                                        <td
                                            style="
                                            width:50%;
                                            vertical-align:middle;
                                            text-align:left;
                                            height:46px;
                                        ">
                                            @if ($senderLogoBase64)
                                                <img src="{{ $senderLogoBase64 }}"
                                                    style="
                                                    height:42px;
                                                    width:auto;
                                                    max-width:190px;
                                                    object-fit:contain;
                                                " />
                                            @endif
                                            @if ($recipientLogoBase64)
                                                <img src="{{ $recipientLogoBase64 }}"
                                                    style="
                                                    height:42px;
                                                    width:auto;
                                                    max-width:190px;
                                                    object-fit:contain;
                                                " />
                                            @endif
                                        </td>

                                    </tr>
                                </table>

                            </td>

                        </tr>
                    </table>

                </td>

                <!-- RIGHT SIDE CONTACT -->
                <td
                    style="
                    width:28%;
                    vertical-align:top;
                    text-align:right;
                    padding-left:10px;
                ">

                    <div
                        style="
                        font-size:9pt;
                        font-weight:bold;
                        color:#000;
                        line-height:1.35;
                    ">
                        {{ $topRightName }}
                    </div>

                    <div
                        style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    ">
                        {{ $topRightStreet }}
                    </div>

                    <div
                        style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    ">
                        {{ $topRightCity }}
                    </div>

                    <div
                        style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    ">
                        {{ $topRightCountry }}
                    </div>

                    <div
                        style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                        margin-top:4px;
                    ">
                        T: {{ $topRightPhone }}
                    </div>

                    <div
                        style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    ">
                        E: {{ $topRightEmail }}
                    </div>

                </td>

            </tr>
        </table><!-- ========================================================= --> <!-- TITLE -->
        <!-- ========================================================= -->
        <div
            style="
            margin-top:18px;
            margin-bottom:12px;
            font-size:20pt;
            font-weight:bold;
            color:#111;
            letter-spacing:0.3px;
        ">
            ORDER
        </div><!-- ========================================================= --> <!-- QUOTATION META -->
        <!-- ========================================================= -->
           <table
        style="
            width:100%;
            border-collapse:collapse;
            border:1px solid #000;
        "
        cellpadding="0"
        cellspacing="0"
    >
        <tr>

            <td
                style="
                    width:65%;
                    border-right:1px solid #000;
                    padding:5px 8px;
                    font-size:8.5pt;
                    background:#fff;
                "
            >
                <span style="font-weight:bold;">
                    Order No.:
                </span>

                {{ $order->order_number }}
            </td>

            <td
                style="
                    width:35%;
                    padding:5px 8px;
                    font-size:8.5pt;
                    background:#fff;
                "
            >
                <span style="font-weight:bold;">
                    Order date:
                </span>

                {{ $order->created_at->format('d M Y') }}
            </td>

        </tr>
    </table> <!-- ========================================================= --> <!-- QUOTATION TO -->
        <!-- ========================================================= -->
        <table style=" width:100%; border-collapse:collapse; border:1px solid #000; margin-top:8px; " cellpadding="0"
            cellspacing="0">
            <tr>
                <td colspan="2"
                    style=" background:#666; color:#fff; font-weight:bold; padding:5px 8px; font-size:9pt; "> Order
                    to: </td>
            </tr>
            <tr> <!-- CUSTOMER ADDRESS -->
                <td
                    style=" width:65%; vertical-align:top; padding:7px 8px; font-size:8.5pt; line-height:1.45; border-right:1px solid #000; ">
                    <div style="font-weight:bold;">  {{ $recipient?->name ?? 'Admin' }} </div>
                    <div>{{ $recipientMeta['address'] ?? '' }}  </div>
                    <div>  {{ $recipientMeta['postal_code'] ?? '' }} {{ $recipientMeta['city'] ?? '' }} </div>
                    <div> {{ $recipientMeta['country'] ?? '' }} </div>
                </td> <!-- CUSTOMER CONTACT -->
                <td style=" width:35%; vertical-align:top; padding:7px 8px; font-size:8.5pt; line-height:1.45; ">
                    <div> Tel.: {{ $reseller->phone ?? '+31404021009' }} </div>
                    <div> Email: {{ $reseller->email ?? 'john@dtmedia.nl' }} </div>
                    <div style="height:5mm;"></div> @php $vat = $resellerMeta['vat_number'] ?? 'NL811021774B01'; @endphp @if ($vat)
                        <div style="margin-top:4px;"> VAT: {{ $vat }} </div>
                    @endif
                </td>
            </tr>
        </table> <!-- ========================================================= --> <!-- ITEMS -->
        <!-- ========================================================= -->
        <table style=" table-layout:fixed; width:100%; border-collapse:collapse; border:1px solid #000; margin-top:8px; " cellpadding="0"
            cellspacing="0">
            <thead>
                <tr> <!-- PRODUCT CODE -->
                    <th
                        style=" background:#666; color:#fff; font-size:7.5pt; font-weight:bold; padding:5px 6px; text-align:left; border-right:1px solid #000; width:15%; ">
                        Product code </th> <!-- PRODUCT NAME -->
                    <th
                        style=" background:#666; color:#fff; font-size:7.5pt; font-weight:bold; padding:5px 6px; text-align:left; border-right:1px solid #000; width:50%; ">
                        Product name </th> <!-- UNIT PRICE -->
                    <th
                        style=" background:#666; color:#fff; font-size:7.5pt; font-weight:bold; padding:5px 6px; text-align:right; border-right:1px solid #000; width:15%; ">
                        Unit price </th> <!-- QUANTITY -->
                    <th
                        style=" background:#666; color:#fff; font-size:7.5pt; font-weight:bold; padding:5px 6px; text-align:center; border-right:1px solid #000; width:5%; ">
                        Qty </th> <!-- TOTAL -->
                    <th
                        style=" background:#666; color:#fff; font-size:7.5pt; font-weight:bold; padding:5px 6px; text-align:right; width:15%; ">
                        Total </th>
                </tr>
            </thead>
            <tbody> <!-- ================================================= --> <!-- ACTUAL ITEMS -->
                <!-- ================================================= -->
                 @foreach ($order->items as $item)
                    @php
                        $p = (float) str_replace([','], '', (string) ($item->getAttributes()['price'] ?? $item->price));
                        $total = $p * (int) $item->quantity;
                    @endphp <tr> <!-- PRODUCT CODE -->
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            {{ $item->product->product_code ?? ($item->product_code ?? '') }} </td>
                        <!-- PRODUCT NAME -->
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            {{ $item->product->product_title ?? ($item->product_title ?? '') }} </td>
                        <!-- UNIT PRICE -->
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; text-align:right; border-right:1px solid #000; border-bottom:1px solid #000; white-space:nowrap; ">
                            {{ $currency }} {{ number_format($p, 2, '.', ',') }} </td> <!-- QUANTITY -->
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            {{ $item->quantity }} </td> <!-- TOTAL -->
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; text-align:right; border-bottom:1px solid #000; white-space:nowrap; ">
                            {{ $currency }} {{ number_format($total, 2, '.', ',') }} </td>
                    </tr>
                @endforeach <!-- ================================================= -->
                <!-- EMPTY ROWS -->
                <!-- ================================================= -->
                 @for ($i = count($order->items); $i < 10; $i++)
                    <tr>
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; border-right:1px solid #000; border-bottom:1px solid #000; height:14px; ">
                            &nbsp; </td>
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            &nbsp; </td>
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; text-align:right; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            &nbsp; </td>
                        <td
                            style=" padding:5px 6px; font-size:7.5pt; text-align:center; border-right:1px solid #000; border-bottom:1px solid #000; ">
                            &nbsp; </td>
                        <td style=" padding:5px 6px; font-size:7.5pt; text-align:right; border-bottom:1px solid #000; ">
                            &nbsp; </td>
                    </tr>
                @endfor
            </tbody>
        </table> <!-- ========================================================= --> <!-- TOTALS -->
        <!-- ========================================================= -->
                   <table align="right" style="width:35%;border-collapse:collapse;font-size:7.5pt;margin-top:4mm;  border:1px solid #000;" cellpadding="0"
            cellspacing="0">
            <tr>
                <td style="border:1px solid #000;font-size:7.5pt;padding:5px 6px;text-align:right;width:57%;">Sub total:</td>
                <td style="border:1px solid #000;font-size:7.5pt;padding:5px 6px;text-align:right;width:43%;">{{ $currency }}&nbsp;
                    {{ number_format($subTotal, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;font-size:7.5pt;padding:5px d6px;text-align:right;">VAT {{ $vatPerc }}%:</td>
                <td style="border:1px solid #000;font-size:7.5pt;padding:5px 6px;text-align:right;">{{ $currency }}&nbsp;
                    {{ number_format($taxAmount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;padding:5px 6px; text-align:right;font-weight:700; font-size:7.5pt;">Grand total:</td>
                <td style="border:1px solid #000;padding:5px 6px;text-align:right;font-weight:700;font-size:7.5pt;">
                    {{ number_format($grandTotal, 2, '.', ',') }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
