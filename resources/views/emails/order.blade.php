@php
    $sender = $order->fromUser ?? ($order->user ?? null);
    $meta = $sender?->userMeta?->metadata ?? [];
    $companyName = $meta['wholesale_company_name'] ?? ($meta['company_name'] ?? $sender?->name ?? 'Test Company Limited');
    $recipientName = $order->toUser?->name ?? 'John';
    $recipientFirstName = trim(explode(' ', $recipientName)[0] ?? $recipientName) ?: 'John';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order {{ $order->order_number }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f4f6;">
    <tr>
        <td align="center" style="padding:32px 16px;">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden;">
                <tr>
                    <td style="padding:32px 40px 24px 40px; font-family:Arial, Helvetica, sans-serif; font-size:15px; line-height:24px; color:#1f2937;">
                        <p style="margin:0 0 16px 0;">Dear {{ $recipientFirstName }},</p>

                        <p style="margin:0 0 16px 0;">This is an automated generated email from bwtattachments.com</p>

                        <p style="margin:0 0 16px 0;">You have received a new order from <strong>{{ $companyName }}</strong></p>

                        @if(!empty($order->order_email_message))
                            <p style="margin:0 0 16px 0; white-space:pre-line;">{{ $order->order_email_message }}</p>
                        @elseif(!empty($order->notes) && strip_tags($order->notes) !== '')
                            <p style="margin:0 0 16px 0;">{{ strip_tags($order->notes) }}</p>
                        @else
                            <p style="margin:0 0 16px 0;">All attachments need to be CAT yellow if possible</p>
                        @endif

                        <p style="margin:24px 0 4px 0;">Best regards,</p>
                        <p style="margin:0; font-weight:700; color:#111827;">BWT</p>
                        <p style="margin:0;"><a href="mailto:sales@bwtattachments.com" style="color:#2563eb; text-decoration:none;">sales@bwtattachments.com</a></p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:16px 40px; background-color:#f9fafb; border-top:1px solid #e5e7eb; font-family:Arial, Helvetica, sans-serif; font-size:12px; line-height:18px; color:#6b7280; text-align:center;">
                        This is an automated email, please do not reply directly. PDF attached.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
