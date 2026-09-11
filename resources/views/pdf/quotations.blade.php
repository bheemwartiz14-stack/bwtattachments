@php
    $sender = $quotation->user;
       // Reseller / recipient
    $reseller = $quotation->reseller ?? null;
    $role = $sender?->roles->first()?->name;
    $senderMeta = $sender?->userMeta?->metadata ?? [];
    // Sender logo
    $senderLogoPath = '';
    $senderLogoBase64 = '';
    $senderCompany = '';

    if ($role === 'Wholesaler') {
        $senderLogoPath = $sender?->getFirstMediaPath('wholesale_client_logo');
        $senderCompany = $senderMeta['wholesale_company_name']
            ?? ($senderMeta['company_name'] ?? '');
    } elseif ($role === 'Reseller') {
        $senderLogoPath = $sender?->getFirstMediaPath('retailer_client_logo');
        $senderCompany = $senderMeta['company_name']
            ?? ($senderMeta['retailer_client_name'] ?? '');
    } else {
        $senderCompany = $senderMeta['company_name'] ?? '';
    }

    if ($senderLogoPath && file_exists($senderLogoPath)) {
        $type = pathinfo($senderLogoPath, PATHINFO_EXTENSION);
        $type = $type ?: 'png';

        $data = @file_get_contents($senderLogoPath);

        if ($data) {
            $senderLogoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    // Reseller / recipient
    $reseller = $quotation->reseller ?? null;
    $resellerMeta = $reseller?->userMeta?->metadata ?? [];

    // Reseller logo
    $resellerLogoPath = $reseller?->getFirstMediaPath('retailer_client_logo');
    $resellerLogoBase64 = '';

    if ($resellerLogoPath && file_exists($resellerLogoPath)) {
        $type = pathinfo($resellerLogoPath, PATHINFO_EXTENSION);
        $type = $type ?: 'png';

        $data = @file_get_contents($resellerLogoPath);

        if ($data) {
            $resellerLogoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    }

    // Top-right company block
    $topRightName = $reseller->name ?? ($senderCompany ?: 'Reseller name');

    $topRightStreet = $resellerMeta['address'] ?? 'Street name';

    $topRightCity = trim(
        ($resellerMeta['postal_code'] ?? '1234AB') . ' ' .
        ($resellerMeta['city'] ?? 'Place')
    );

    $topRightCountry = $resellerMeta['country'] ?? 'Country';

    $topRightPhone = $reseller->phone
        ?? ($resellerMeta['phone'] ?? '+31620315250');

    $topRightEmail = $reseller->email
        ?? 'john@unit84.com';

    // Quotation recipient block
    $custName = $reseller->name ?? '';

    $custAddressLine1 = $resellerMeta['address']
        ?? 'Korte kerkstraat 6';

    $custAddressLine2 = trim(
        ($resellerMeta['postal_code'] ?? '5524AX') . ' ' .
        ($resellerMeta['city'] ?? 'Steensel')
    );

    $custAddressLine3 = $resellerMeta['country']
        ?? 'The Netherlands';

    // Totals
    $rawSub = $quotation->getAttributes()['sub_total'] ?? 0;
    $rawTax = $quotation->getAttributes()['tax_amount'] ?? 0;
    $rawGrand = $quotation->getAttributes()['grand_total'] ?? 0;
    $rawVatPerc = $quotation->getAttributes()['vat_percentage'] ?? 0;

    $subTotal = (float) str_replace(
        [','],
        '',
        (string) $rawSub
    );

    $taxAmount = (float) str_replace(
        [','],
        '',
        (string) $rawTax
    );

    $grandTotal = (float) str_replace(
        [','],
        '',
        (string) $rawGrand
    );

    $vatPerc = (string) str_replace(
        [','],
        '',
        (string) $rawVatPerc
    );

    $currency = config('app.currency_symbol', '€');

    $rowCount = max(
        15,
        count($quotation->items) + 5
    );
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>
        Quotation {{ $quotation->quotation_number }}
    </title>

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
    "
>

<div
    style="
        width:100%;
        margin:0;
        padding:0;
    "
>

    <!-- ========================================================= -->
    <!-- TOP SECTION: LOGOS + RECIPIENT CONTACT -->
    <!-- ========================================================= -->

    <table
        style="width:100%;border-collapse:collapse;"
        cellpadding="0"
        cellspacing="0"
    >
        <tr>

            <!-- LEFT SIDE -->
            <td
                style="
                    width:72%;
                    vertical-align:top;
                    padding-right:10px;
                "
            >

                <table
                    style="width:100%;border-collapse:collapse;"
                    cellpadding="0"
                    cellspacing="0"
                >
                    <tr>

                        <td
                            style="
                                padding:4px 8px;
                                vertical-align:top;
                            "
                        >

                            <table
                                style="width:100%;border-collapse:collapse;"
                                cellpadding="0"
                                cellspacing="0"
                            >
                                <tr>

                                    <!-- SENDER LOGO -->
                                    <td
                                        style="
                                            width:50%;
                                            vertical-align:middle;
                                            text-align:left;
                                            height:46px;
                                        "
                                    >
                                        @if ($senderLogoBase64)
                                            <img
                                                src="{{ $senderLogoBase64 }}"
                                                style="
                                                    height:42px;
                                                    width:auto;
                                                    max-width:190px;
                                                    object-fit:contain;
                                                "
                                            />
                                        @endif
                                         @if ($resellerLogoBase64)
                                            <img
                                                src="{{ $resellerLogoBase64 }}"
                                                style="
                                                    height:42px;
                                                    width:auto;
                                                    max-width:190px;
                                                    object-fit:contain;
                                                "
                                            />
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
                "
            >

                <div
                    style="
                        font-size:9pt;
                        font-weight:bold;
                        color:#000;
                        line-height:1.35;
                    "
                >
                    {{ $topRightName }}
                </div>

                <div
                    style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    "
                >
                    {{ $topRightStreet }}
                </div>

                <div
                    style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    "
                >
                    {{ $topRightCity }}
                </div>

                <div
                    style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    "
                >
                    {{ $topRightCountry }}
                </div>

                <div
                    style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                        margin-top:4px;
                    "
                >
                    T: {{ $topRightPhone }}
                </div>

                <div
                    style="
                        font-size:8.5pt;
                        color:#000;
                        line-height:1.35;
                    "
                >
                    E: {{ $topRightEmail }}
                </div>

            </td>

        </tr>
    </table>


    <!-- ========================================================= -->
    <!-- TITLE -->
    <!-- ========================================================= -->

    <div
        style="
            margin-top:18px;
            margin-bottom:12px;
            font-size:20pt;
            font-weight:bold;
            color:#111;
            letter-spacing:0.3px;
        "
    >
        QUOTATION
    </div>


    <!-- ========================================================= -->
    <!-- QUOTATION META -->
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
                    width:50%;
                    border-right:1px solid #000;
                    padding:5px 8px;
                    font-size:8.5pt;
                    background:#fff;
                "
            >
                <span style="font-weight:bold;">
                    Quote No.:
                </span>

                {{ $quotation->quotation_number }}
            </td>

            <td
                style="
                    width:50%;
                    padding:5px 8px;
                    font-size:8.5pt;
                    background:#fff;
                "
            >
                <span style="font-weight:bold;">
                    Quote date:
                </span>

                {{ $quotation->created_at->format('d M Y') }}
            </td>

        </tr>
    </table>


    <!-- ========================================================= -->
    <!-- QUOTATION TO -->
    <!-- ========================================================= -->

    <table
        style="
            width:100%;
            border-collapse:collapse;
            border:1px solid #000;
            margin-top:8px;
        "
        cellpadding="0"
        cellspacing="0"
    >

        <tr>

            <td
                colspan="2"
                style="
                    background:#666;
                    color:#fff;
                    font-weight:bold;
                    padding:5px 8px;
                    font-size:9pt;
                "
            >
                Quotation to:
            </td>

        </tr>

        <tr>

            <!-- CUSTOMER ADDRESS -->
            <td
                style="
                    width:55%;
                    vertical-align:top;
                    padding:7px 8px;
                    font-size:8.5pt;
                    line-height:1.45;
                    border-right:1px solid #000;
                "
            >

                <div style="font-weight:bold;">
                    {{ $custName ?: 'Storm buckets' }}
                </div>

                <div>
                    John van de Wiel
                </div>

                <div>
                    {{ $custAddressLine1 }}
                </div>

                <div>
                    {{ $custAddressLine2 }}
                </div>

                <div>
                    {{ $custAddressLine3 }}
                </div>

            </td>


            <!-- CUSTOMER CONTACT -->
            <td
                style="
                    width:45%;
                    vertical-align:top;
                    padding:7px 8px;
                    font-size:8.5pt;
                    line-height:1.45;
                "
            >

                <div>
                    Tel.:
                    {{ $reseller->phone ?? '+31404021009' }}
                </div>

                <div>
                    Email:
                    {{ $reseller->email ?? 'john@dtmedia.nl' }}
                </div>

                    <div style="height:5mm;"></div>
                @php
                    $vat = $resellerMeta['vat_number']
                        ?? 'NL811021774B01';
                @endphp


                @if ($vat)
                    <div style="margin-top:4px;">
                        VAT: {{ $vat }}
                    </div>
                @endif

            </td>

        </tr>

    </table>


    <!-- ========================================================= -->
    <!-- ITEMS -->
    <!-- ========================================================= -->

    <table
        style="
            width:100%;
            border-collapse:collapse;
            border:1px solid #000;
            margin-top:8px;
        "
        cellpadding="0"
        cellspacing="0"
    >

        <thead>

            <tr>

                <!-- PRODUCT CODE -->
                <th
                    style="
                        background:#666;
                        color:#fff;
                        font-size:7.5pt;
                        font-weight:bold;
                        padding:5px 6px;
                        text-align:left;
                        border-right:1px solid #000;
                        width:10%;
                    "
                >
                    Product code
                </th>


                <!-- PRODUCT NAME -->
                <th
                    style="
                        background:#666;
                        color:#fff;
                        font-size:7.5pt;
                        font-weight:bold;
                        padding:5px 6px;
                        text-align:left;
                        border-right:1px solid #000;
                        width:34%;
                    "
                >
                    Product name
                </th>


                <!-- UNIT PRICE -->
                <th
                    style="
                        background:#666;
                        color:#fff;
                        font-size:7.5pt;
                        font-weight:bold;
                        padding:5px 6px;
                        text-align:right;
                        border-right:1px solid #000;
                        width:10%;
                    "
                >
                    Unit price
                </th>


                <!-- QUANTITY -->
                <th
                    style="
                        background:#666;
                        color:#fff;
                        font-size:7.5pt;
                        font-weight:bold;
                        padding:5px 6px;
                        text-align:center;
                        border-right:1px solid #000;
                        width:5%;
                    "
                >
                    Qty
                </th>


                <!-- TOTAL -->
                <th
                    style="
                        background:#666;
                        color:#fff;
                        font-size:7.5pt;
                        font-weight:bold;
                        padding:5px 6px;
                        text-align:right;
                             width:10%;
                    "
                >
                    Total
                </th>

            </tr>

        </thead>


        <tbody>

            <!-- ================================================= -->
            <!-- ACTUAL ITEMS -->
            <!-- ================================================= -->

            @foreach ($quotation->items as $item)

                @php
                    $p = (float) str_replace(
                        [','],
                        '',
                        (string) (
                            $item->getAttributes()['price']
                            ?? $item->price
                        )
                    );

                    $total = $p * (int) $item->quantity;
                @endphp

                <tr>

                    <!-- PRODUCT CODE -->
                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        {{ $item->product->product_code ?? ($item->product_code ?? '') }}
                    </td>


                    <!-- PRODUCT NAME -->
                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        {{ $item->product->product_title ?? ($item->product_title ?? '') }}
                    </td>


                    <!-- UNIT PRICE -->
                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:right;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                            white-space:nowrap;
                        "
                    >
                        {{ $currency }}
                        {{ number_format($p, 2, '.', ',') }}
                    </td>


                    <!-- QUANTITY -->
                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:center;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        {{ $item->quantity }}
                    </td>


                    <!-- TOTAL -->
                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:right;
                            border-bottom:1px solid #000;
                            white-space:nowrap;
                        "
                    >
                        {{ $currency }}
                        {{ number_format($total, 2, '.', ',') }}
                    </td>

                </tr>

            @endforeach


            <!-- ================================================= -->
            <!-- EMPTY ROWS -->
            <!-- ================================================= -->

            @for ($i = count($quotation->items); $i < 3; $i++)

                <tr>

                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                            height:14px;
                        "
                    >
                        &nbsp;
                    </td>

                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        &nbsp;
                    </td>

                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:right;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        &nbsp;
                    </td>

                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:center;
                            border-right:1px solid #000;
                            border-bottom:1px solid #000;
                        "
                    >
                        &nbsp;
                    </td>

                    <td
                        style="
                            padding:5px 6px;
                            font-size:7.5pt;
                            text-align:right;
                            border-bottom:1px solid #000;
                        "
                    >
                        &nbsp;
                    </td>

                </tr>

            @endfor

        </tbody>

    </table>


    <!-- ========================================================= -->
    <!-- TOTALS -->
    <!-- ========================================================= -->
        <table align="right" style="width:37%;border-collapse:collapse;font-size:12px;margin-top:4mm;  border:1px solid #000;" cellpadding="0"
            cellspacing="0">
            <tr>
                <td style="border:1px solid #000;padding:2mm;text-align:right;width:50%;">Sub total:</td>
                <td style="border:1px solid #000;padding:2mm;text-align:right;width:50%;">{{ $currency }}&nbsp;
                    {{ number_format($subTotal, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;padding:2mm;text-align:right;">VAT {{ $vatPerc }}%:</td>
                <td style="border:1px solid #000;padding:2mm;text-align:right;">{{ $currency }}&nbsp;
                    {{ number_format($taxAmount, 2, '.', ',') }}</td>
            </tr>
            <tr>
                <td style="border:1px solid #000;padding:2mm;text-align:right;font-weight:700;">Grand total:</td>
                <td style="border:1px solid #000;padding:2mm;text-align:right;font-weight:700;">
                    {{ number_format($grandTotal, 2, '.', ',') }}</td>
            </tr>
        </table>

</div>

</body>

</html>
