<x-mail::message>
# Quotation {{ $quotation->quotation_number }}

Dear {{ $quotation->contact_name }},

Please find attached the quotation from **BWT** for your review.

**Quotation Details:**
- **Number:** {{ $quotation->quotation_number }}
- **Issue Date:** {{ $quotation->issue_date?->format('d M Y') }}
- **Valid Until:** {{ $quotation->valid_until?->format('d M Y') }}

@if($quotation->notes)
**Notes:**
{{ $quotation->notes }}
@endif

If you have any questions, please don't hesitate to reach out.

Thanks,<br>
{{ $quotation->user->name }}<br>
BWT
</x-mail::message>
