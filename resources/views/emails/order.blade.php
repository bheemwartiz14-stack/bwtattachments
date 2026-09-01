@php
    $sender = $order->fromUser ?? ($order->user ?? null);
    $role = $sender?->roles->first()?->name;
    $meta = $sender?->userMeta?->metadata ?? [];
    $logoUrl = '';
    if ($role === 'Wholesaler') {
        $logoUrl = $sender?->getFirstMediaUrl('wholesale_client_logo');
        $companyName = $meta['wholesale_company_name'] ?? ($meta['company_name'] ?? '—');
        $senderLabel = 'Wholesaler';
    } elseif ($role === 'Reseller') {
        $logoUrl = $sender?->getFirstMediaUrl('retailer_client_logo');
        $companyName = $meta['company_name'] ?? ($meta['retailer_client_name'] ?? '—');
        $senderLabel = 'Reseller';
    } else {
        $logoUrl = '';
        $companyName = $meta['company_name'] ?? ($meta['customer_client_name'] ?? '—');
        $senderLabel = 'Customer';
    }
    $addressParts = array_filter([
        $meta['address'] ?? null,
        $meta['postal_code'] ?? null,
        $meta['city'] ?? null,
        $meta['country'] ?? null,
    ]);
    $addressLine = implode(', ', $addressParts);
    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $appName = config('app.name');
    $senderName = config('mail.from.name', $appName);
@endphp

@include('emails.partials.header', [
    'pageTitle' => 'Order ' . $order->order_number,
    'previewText' => "Order {$order->order_number} from {$companyName} — details inside.",
    'heroHeading' => "Order<br>{$order->order_number}",
    'heroSubtitle' => 'Please find your order details below.',
])

<!-- Intro -->
<tr>
    <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
        <p
            style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
            Dear <strong style="color:#111827;">{{ $order->toUser->name}}</strong>,
        </p>
        <p
            style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
            You have received a new order from <strong>{{ $companyName }}</strong> ({{ $sender->email }}). Details
            below.
        </p>
    </td>
</tr>

@if (!empty($order->order_email_message))
    <tr>
        <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                style="background-color:#eef2ff; border-radius:14px; border:1px solid #e0e7ff;">
                <tr>
                    <td
                        style="padding:20px 24px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:22px; color:#4338ca;">
                        {!! nl2br(e($order->order_email_message)) !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
@endif

<!-- Order Details -->
<tr>
    <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
            style="background-color:#f9fafb; border-radius:14px;">
            <tr>
                <td class="details-cell" style="padding:22px 24px;">
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af;">
                                Order Number</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right;">
                                {{ $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af;">
                                Order Date</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right;">
                                {{ $order->order_date?->format('d M Y') ?? $order->created_at->format('d M Y') }}</td>
                        </tr>
                        @if ($order->order_reference)
                            <tr>
                                <td colspan="2"
                                    style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="row-label"
                                    style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af;">
                                    Reference</td>
                                <td class="row-value"
                                    style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right;">
                                    {{ $order->order_reference }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af;">
                                Grand Total</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right;">
                                {{ config('app.currency_symbol') }}
                                {{ number_format((float) str_replace(',', '', (string) ($order->getAttributes()['grand_total'] ?? $order->grand_total)), 2) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td class="fluid-padding" style="padding:22px 40px 40px 40px;">
        <p
            style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#4b5563;">
            Please check the attached PDF for full order details.
        </p>
    </td>
</tr>

@include('emails.partials.footer')
