@php
    $sender = $quotation->user;
    $senderMeta = $sender?->userMeta?->metadata ?? [];
    $roleName = $sender?->roles->pluck('name')->first();
    $senderLogoPath = match ($roleName) {
        'Wholesaler' => $sender?->getFirstMediaPath('wholesale_client_logo'),
        'Reseller' => $sender?->getFirstMediaPath('retailer_client_logo'),
        default => null,
    };
    $defaultLogoPath = public_path('images/BIG.jpg');
    $logoPath = $senderLogoPath ?: $defaultLogoPath;
    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
    $data = file_get_contents($logoPath);
    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

    $senderName = $senderMeta['company_name'] ?? $sender->name ?? '';
    $senderAddressParts = array_filter([
        $senderMeta['address'] ?? null,
        $senderMeta['city'] ?? null,
        $senderMeta['country'] ?? null,
    ]);
    $senderAddress = implode(', ', $senderAddressParts);
    $senderPhone = $senderMeta['phone'] ?? $sender->phone ?? '';
    $senderEmail = $sender->email ?? '';

    $reseller = $quotation->reseller ?? null;
    $meta = $reseller?->userMeta?->metadata ?? [];

    $custAddressParts = array_filter([
        $meta['address'] ?? null,
        $meta['city'] ?? null,
        $meta['country'] ?? null,
    ]);
    $custAddress = implode(', ', $custAddressParts);
    $subTotal = (float) str_replace(',', '', $quotation->sub_total);
    $taxAmount = (float) str_replace(',', '', $quotation->tax_amount);
    $grandTotal = (float) str_replace(',', '', $quotation->grand_total);
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Quotation</title>
</head>

<body style="margin:0;padding:40px;font-family:Arial,Helvetica,sans-serif;color:#231f20;background:#fff;font-size:14px;">

    <div style="width:100%;max-width:1160px;margin:auto;">

        <!-- Header -->
        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td style="width:70%;vertical-align:top;">

                    <div style="font-size:20px;font-weight:bold;">
                        {{ $senderName }}
                    </div>

                    @if($senderAddress)
                    <div style="font-size:15px;margin-top:6px;line-height:1.5;">
                        {{ $senderAddress }}
                    </div>
                    @endif

                    @if($senderPhone)
                    <div style="font-size:15px;line-height:1.5;">
                        {{ $senderPhone }}
                    </div>
                    @endif

                    @if($senderEmail)
                    <div style="font-size:15px;line-height:1.5;">
                        {{ $senderEmail }}
                    </div>
                    @endif

                </td>

                <td style="width:30%;text-align:right;vertical-align:top;">
                    <img src="{{ $logoBase64 }}" style="width:170px;">
                </td>
            </tr>
        </table>

        <!-- Title -->
        <div style="font-size:46px;font-weight:900;margin-top:30px;margin-bottom:30px;">
            Quotation
        </div>

        <!-- Quote Details -->
        <table style="width:100%;border-collapse:collapse;border:1px solid #b3b3b3;margin-bottom:20px;">
            <tr>
                <td style="width:50%;padding:12px 16px;font-size:16px;font-weight:bold;border-right:1px solid #b3b3b3;">
                    Quote No.: {{ $quotation->quotation_number }}
                </td>
                <td style="width:50%;padding:12px 16px;font-size:16px;font-weight:bold;">
                    Quote Date: {{ $quotation->created_at->format('d M Y') }}
                </td>
            </tr>
        </table>

        <!-- Customer -->
        <table style="width:100%;border-collapse:collapse;border:1px solid #b3b3b3;margin-bottom:20px;">
            <tr>
                <td colspan="2" style="background:#0057a3;color:#fff;font-weight:bold;padding:10px 16px;font-size:16px;">
                    Quotation to:
                </td>
            </tr>
            <tr>
                <td style="width:60%;padding:14px 16px;vertical-align:top;font-size:15px;line-height:1.7;">
                    <div style="font-weight:bold;">{{ $reseller->name ?? '' }}</div>
                    <div>{{ $custAddress }}</div>
                    <div>{{ $reseller->email ?? '' }}</div>
                </td>
                <td style="width:40%;padding:14px 16px;vertical-align:top;font-size:15px;line-height:1.7;">
                    @if(!empty($meta['vat_number']))
                        <div>VAT: {{ $meta['vat_number'] }}</div>
                    @endif
                    @if(!empty($reseller->phone))
                        <div>{{ $reseller->phone }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table style="width:100%;border-collapse:collapse;border:1px solid #b3b3b3;">
            <thead>
                <tr style="background:#0057a3;color:#fff;">
                    <th style="padding:10px 14px;text-align:left;font-size:15px;width:15%;">Product code</th>
                    <th style="padding:10px 14px;text-align:left;font-size:15px;width:40%;">Product name</th>
                    <th style="padding:10px 14px;text-align:right;font-size:15px;width:15%;">Unit price</th>
                    <th style="padding:10px 14px;text-align:center;font-size:15px;width:10%;">Qty</th>
                    <th style="padding:10px 14px;text-align:right;font-size:15px;width:20%;">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($quotation->items as $item)
                    @php
                        $itemPrice = (float) str_replace(',', '', $item->price);
                    @endphp
                    <tr style="vertical-align:top;">
                        <td style="padding:12px 14px;font-size:14px;border-bottom:1px solid #b3b3b3;">
                            {{ $item->product->product_code }}
                        </td>
                        <td style="padding:12px 14px;font-size:14px;border-bottom:1px solid #b3b3b3;">
                            {{ $item->product->product_title }}
                        </td>
                        <td style="padding:12px 14px;font-size:14px;text-align:right;border-bottom:1px solid #b3b3b3;white-space:nowrap;">
                            € {{ number_format($itemPrice, 2, '.', ',') }}
                        </td>
                        <td style="padding:12px 14px;font-size:14px;text-align:center;border-bottom:1px solid #b3b3b3;">
                            {{ $item->quantity }}
                        </td>
                        <td style="padding:12px 14px;font-size:14px;text-align:right;border-bottom:1px solid #b3b3b3;white-space:nowrap;">
                            € {{ number_format($itemPrice * $item->quantity, 2, '.', ',') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bottom -->
        <table style="width:100%;margin-top:0;border-collapse:collapse;">
            <tr>
                <!-- Terms -->
                <td style="width:60%;vertical-align:top;padding-top:14px;padding-right:20px;">
                    <div style="font-size:15px;line-height:1.6;">
                        Terms and Conditions: By accepting this quotation, you agree to our terms and
                        conditions which you can view here.
                    </div>
                </td>

                <!-- Totals -->
                <td style="width:40%;vertical-align:top;padding-top:14px;">
                    <table style="width:100%;border-collapse:collapse;border:1px solid #b3b3b3;">
                        <tr>
                            <td style="padding:10px 16px;text-align:right;font-size:15px;border-bottom:1px solid #b3b3b3;">
                                Sub total:
                            </td>
                            <td style="padding:10px 16px;text-align:right;font-size:15px;border-bottom:1px solid #b3b3b3;white-space:nowrap;">
                                € {{ number_format($subTotal, 2, '.', ',') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 16px;text-align:right;font-size:15px;border-bottom:1px solid #b3b3b3;">
                                VAT {{ $quotation->vat_percentage }}%:
                            </td>
                            <td style="padding:10px 16px;text-align:right;font-size:15px;border-bottom:1px solid #b3b3b3;white-space:nowrap;">
                                € {{ number_format($taxAmount, 2, '.', ',') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px 16px;text-align:right;font-size:16px;font-weight:bold;">
                                Grand total:
                            </td>
                            <td style="padding:10px 16px;text-align:right;font-size:16px;font-weight:bold;white-space:nowrap;">
                                € {{ number_format($grandTotal, 2, '.', ',') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>