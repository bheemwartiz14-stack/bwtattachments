@php
    $sender = $order->fromUser ?? ($order->user ?? null);
    $meta = $sender?->userMeta?->metadata ?? [];
    $companyName = $meta['wholesale_company_name'] ?? ($meta['company_name'] ?? $sender?->name ?? 'Test Company Limited');
    $recipientName = $order->toUser?->name ?? 'John';
    $recipientFirstName = trim(explode(' ', $recipientName)[0] ?? $recipientName) ?: 'John';
    $customMessage = $order->order_email_message
        ?? ((!empty($order->notes) && strip_tags($order->notes) !== '') ? strip_tags($order->notes) : 'All attachments need to be CAT yellow if possible');
@endphp
Dear {{ $recipientFirstName }},

This is an automated generated email from bwtattachments.com

You have received a new order {{ $order->order_number }} from {{ $companyName }}.

{{ $customMessage }}

Best regards,
BWT
sales@bwtattachments.com

---
This is an automated email, please do not reply directly. PDF attached.
