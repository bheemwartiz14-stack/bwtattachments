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

        $reseller = $quotation->reseller ?? null;
        $meta = $reseller->usermeta->metadata ?? [];
    @endphp
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Quotation</title>
    </head>

    <body
        style="margin:0;padding:40px;font-family:Arial,Helvetica,sans-serif;color:#222;background:#fff;font-size:14px;">

        <div style="width:100%;max-width:1000px;margin:auto;">

            <!-- Header -->
            <table style="width:100%;border-collapse:collapse;">
                <tr>
                    <td style="width:70%;vertical-align:top;">

                        <div style="font-size:20px;font-weight:bold;">
                            BIG Work Tools
                        </div>

                        <div style="font-size:16px;margin-top:4px;">
                            {{ $address }}
                        </div>

                        <div style="font-size:16px;margin-top:4px;">
                            {{ $settings->support_phone }}
                        </div>

                        <div style="font-size:16px;margin-top:4px;">
                            {{ $settings->support_email }}
                        </div>

                    </td>

                    <td style="width:30%;text-align:right;vertical-align:top;">
                        <img src="{{ $base64 }}" style="width:170px;">

                    </td>
                </tr>
            </table>

            <!-- Title -->

            <div style="font-size:64px;font-weight:bold;margin-top:40px;margin-bottom:40px;">
                Quotation
            </div>

            <!-- Quote Details -->

            <table style="width:100%;border-collapse:collapse;border:1px solid #777;margin-bottom:30px;">
                <tr>

                    <td style="width:60%;padding:10px;font-size:18px;font-weight:bold;">
                        Quote No.: {{ $quotation->quotation_number }}
                    </td>

                    <td style="width:40%;padding:10px;font-size:18px;font-weight:bold;">
                        Quote Date: {{ $quotation->created_at->format('d M Y') }}
                    </td>

                </tr>
            </table>

            <!-- Customer -->

            <table style="width:100%;border-collapse:collapse;border:1px solid #777;margin-bottom:35px;">

                <tr>

                    <td colspan="2"
                        style="background:#0057a8;color:#fff;font-weight:bold;padding:8px 10px;font-size:18px;">
                        Quotation to:
                    </td>

                </tr>

                <tr>

                    <td style="width:55%;padding:10px;vertical-align:top;font-size:18px;">
                        {{ $reseller->name ?? '' }}
                        <br>
                        {{ $meta['address'] ?? '' }}, {{ $meta['pin_code'] ?? '' }}, {{ $meta['city'] ?? '' }},
                        {{ $meta['country'] ?? '' }}<br>
                        {{ $reseller->email ?? '' }}
                    </td>
                    <td style="width:45%;padding:10px;vertical-align:top;font-size:18px;">

                        {{ $meta['vat_number'] ?? '' }}<br>

                        {{ $reseller->phone ?? '' }}

                    </td>

                </tr>

            </table>

            <!-- Items -->

            <table style="width:100%;border-collapse:collapse;border:1px solid #777;">

                <thead>
                    <tr style="background:#0057a8;color:white;">

                        <th style="border:1px solid #555;padding:8px;text-align:left;font-size:18px;">
                            Item
                        </th>

                        <th style="border:1px solid #555;padding:8px;text-align:left;font-size:18px;">
                            Description
                        </th>

                        <th style="border:1px solid #555;padding:8px;text-align:center;font-size:18px;">
                            Unit price
                        </th>

                        <th style="border:1px solid #555;padding:8px;text-align:center;font-size:18px;">
                            Qty
                        </th>

                        <th style="border:1px solid #555;padding:8px;text-align:right;font-size:18px;">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody>
                    @foreach ($quotation->items as $item)
                        <tr style="height:700px;vertical-align:top;">

                            <td style="border:1px solid #777;padding:10px;font-size:18px;">
                           {{ $item->product->product_title }}
                            </td>

                            <td style="border:1px solid #777;padding:10px;font-size:18px;">
                               {{ $item->product->product_description }}
                            </td>

                            <td style="border:1px solid #777;padding:10px;text-align:center;font-size:18px;">
                                € {{ number_format($item->price, 2, ',', '.') }}
                            </td>

                            <td style="border:1px solid #777;padding:10px;text-align:center;font-size:18px;">
                                {{ $item->quantity }}
                            </td>

                            <td style="border:1px solid #777;padding:10px;text-align:right;font-size:18px;">
                                 € {{ number_format($item->price * $item->quantity, 2, ',', '.') }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

            <!-- Bottom -->

            <table style="width:100%;margin-top:15px;border-collapse:collapse;">

                <tr>

                    <!-- Terms -->

                    <td style="width:62%;vertical-align:top;padding-right:20px;">

                        <div style="font-size:18px;line-height:1.4;">

                            Terms and Conditions:
                            By accepting this quotation, you agree to our terms and
                            conditions which you can view here.

                        </div>

                    </td>

                    <!-- Totals -->

                    <td style="width:38%;">

                        <table style="width:100%;border-collapse:collapse;">

                            <tr>

                                <td style="border:1px solid #777;padding:10px;text-align:right;font-size:18px;">
                                    Sub total:
                                </td>

                                <td style="border:1px solid #777;padding:10px;text-align:right;font-size:18px;">
                                  € {{ $quotation->sub_total }}
                                </td>

                            </tr>

                            <tr>

                                <td style="border:1px solid #777;padding:10px;text-align:right;font-size:18px;">
                                  VAT ({{ $quotation->vat_percentage }}%)
                                </td>

                                <td style="border:1px solid #777;padding:10px;text-align:right;font-size:18px;">
                                   € {{ $quotation->tax_amount }}
                                </td>

                            </tr>

                            <tr>

                                <td
                                    style="border:1px solid #777;padding:10px;text-align:right;font-size:20px;font-weight:bold;">
                                    Grand total:
                                </td>

                                <td
                                    style="border:1px solid #777;padding:10px;text-align:right;font-size:20px;font-weight:bold;">
                                    € {{ $quotation->grand_total }}
                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>

            </table>

        </div>

    </body>

    </html>
