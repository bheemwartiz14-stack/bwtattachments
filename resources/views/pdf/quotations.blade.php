<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quotation</title>

    <style>
        @page {
            margin: 10mm;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12.8px;
            color: #2b2b2b;
        }

        .wrapper {
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            /* IMPORTANT FOR PDF */
        }

        td,
        th {
            padding: 6px;
            vertical-align: top;
        }

        /* HEADER */
        .header td {
            border: none;
        }

        .company h2 {
            font-size: 18px;
            color: #0a5aa7;
            margin-bottom: 4px;
        }

        .company p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }

        .logo {
            text-align: right;
        }

        .logo img {
            width: 105px;
        }

        /* TITLE */
        .title {
            margin-top: 12px;
            background: #0a5aa7;
            color: #fff;
            padding: 8px 10px;
            font-size: 20px;
            font-weight: bold;
        }

        /* INFO */
        .info {
            margin-top: 10px;
            background: #f4f6f8;
            border-left: 4px solid #0a5aa7;
        }

        .info td {
            padding: 8px;
        }

        /* CUSTOMER */
        .box {
            margin-top: 12px;
            border: 1px solid #ddd;
        }

        .box-title {
            background: #f0f4f8;
            color: #0a5aa7;
            font-weight: bold;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .box-content td {
            padding: 10px;
        }

        .muted {
            color: #666;
        }

        /* ITEMS TABLE */
        .items {
            margin-top: 14px;
        }

        .items th {
            background: #0a5aa7;
            color: #fff;
            padding: 8px;
            font-size: 12px;
        }

        .items td {
            border-bottom: 1px solid #e5e5e5;
            padding: 8px;

            /* ✅ FIX DESCRIPTION ISSUE */
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }

        /* column alignment helpers */
        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        /* FOOTER */
        .footer {
            margin-top: 15px;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 8px;
            background: #fafafa;
        }

        .summary-box table td {
            padding: 4px 0;
        }
    </style>
</head>

<body>

    @php
        $path = public_path('images/BIG.jpg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $settings = app(\App\Settings\GeneralSettings::class);

        $address = trim(
            ($settings->address_line_1 ?? '') .
                ', ' .
                ($settings->address_line_2 ?? '') .
                ', ' .
                ($settings->city ?? '') .
                ', ' .
                ($settings->state ?? '') .
                ' - ' .
                ($settings->pin_code ?? '') .
                ', ' .
                ($settings->country ?? ''),
        );

        $reseller = $quotation->user ?? null;
        $meta = $reseller->usermeta->metadata ?? [];
    @endphp

    <div class="wrapper">

        <!-- HEADER -->
        <table class="header">
            <tr>
                <td width="70%">
                    <div class="company">
                        <h2>{{ $settings->site_title ?? 'BIG Work Tools' }}</h2>
                        <p>{{ $address }}</p>
                        <p>{{ $settings->support_phone }}</p>
                        <p>{{ $settings->support_email }}</p>
                    </div>
                </td>

                <td width="30%" class="logo">
                    <img src="{{ $base64 }}" alt="Logo">
                </td>
            </tr>
        </table>

        <!-- TITLE -->
        <div class="title">QUOTATION</div>

        <!-- INFO -->
        <table class="info">
            <tr>
                <td><strong>Quote No:</strong> {{ $quotation->quotation_number }}</td>
                <td style="text-align:right;">
                    <strong>Date:</strong> {{ $quotation->created_at->format('d M Y') }}
                </td>
            </tr>
        </table>

        <!-- CUSTOMER -->
        <div class="box">
            <div class="box-title">Bill To</div>

            <table class="box-content">
                <tr>
                    <td width="60%">
                        <strong>{{ $reseller->name ?? '' }}</strong><br><br>

                        <span class="muted">Address:</span> {{ $meta['address'] ?? '' }}<br>
                        <span class="muted">City:</span> {{ $meta['city'] ?? '' }}, {{ $meta['pin_code'] ?? '' }}<br>
                        <span class="muted">Country:</span> {{ $meta['country'] ?? '' }}
                    </td>

                    <td width="40%">
                        <strong>VAT:</strong> {{ $meta['vat_number'] ?? '' }}<br><br>
                        <strong>Phone:</strong> {{ $reseller->phone ?? '' }}<br><br>
                        <strong>Email:</strong> {{ $reseller->email ?? '' }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- ITEMS -->
        <table class="items">
            <thead>
                <tr>
                    <th width="18%">Item</th>
                    <th width="44%">Description</th>
                    <th width="15%" class="right">Unit Price</th>
                    <th width="8%" class="center">Qty</th>
                    <th width="15%" class="right">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($quotation->items as $item)
                    <tr>
                        <td>{{ $item->product->product_code }}</td>

                        <td style="word-break: break-word; white-space: normal;">
                            {{ $item->product->product_description }}
                        </td>

                        <td class="right">
                            € {{ number_format($item->price, 2, ',', '.') }}
                        </td>

                        <td class="center">
                            {{ $item->quantity }}
                        </td>

                        <td class="right">
                            € {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- FOOTER -->
        <div class="footer">

            <table>
                <tr>

                    <td width="60%">
                        <strong>Terms & Conditions</strong><br>

                        By accepting this quotation, you agree to our terms and conditions.

                       {{ $quotation->customer_terms }}
                    </td>

                      <td width="60%">
                        <strong>CUSTOMEMR nOTES </strong><br>


                       {{ $quotation->notes }}
                    </td>

                    <td width="40%">
                        <div class="summary-box">

                            <table width="100%">
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="right">€ {{ $quotation->sub_total }}</td>
                                </tr>
                                 <tr>
                                    <td>Margin ({{ $quotation->margin_percentage }}%)</td>
                                    <td class="right">€ {{ $quotation->margin_amount }}</td>
                                </tr>
                                 <tr>
                                    <td>VAT ({{ $quotation->vat_percentage }}%)</td>
                                    <td class="right">€ {{ $quotation->tax_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                     <td class="right">€ {{ $quotation->grand_total }}</td>
                                </tr>
                            </table>
                        </div>
                    </td>

                </tr>
            </table>
        </div>

    </div>

</body>

</html>
