@php
    $sender = $quotation->user;
    $role = $sender?->roles->first()?->name;
    $meta = $sender?->userMeta?->metadata ?? [];

    if ($role === 'Wholesaler') {
        $companyName = $meta['wholesale_company_name'] ?? $meta['company_name'] ?? '—';
        $senderLabel = 'Wholesaler';
    } elseif ($role === 'Reseller') {
        $companyName = $meta['company_name'] ?? $meta['retailer_client_name'] ?? '—';
        $senderLabel = 'Reseller';
    } else {
        $companyName = $meta['company_name'] ?? $meta['customer_client_name'] ?? '—';
        $senderLabel = 'Customer';
    }

    $logoUrl = $sender?->userMeta?->getFirstMediaUrl('wholesale_client_logo');
    if ($logoUrl && str_starts_with($logoUrl, '/')) {
        $logoUrl = config('app.url') . $logoUrl;
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
    'pageTitle' => 'Quotation ' . $quotation->quotation_number,
    'previewText' => "Quotation {$quotation->quotation_number} from {$companyName} — details inside.",
    'heroHeading' => "Quotation<br>{$quotation->quotation_number}",
    'heroSubtitle' => 'Please find your quotation details below.',
])

<!-- Intro -->
<tr>
  <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
    <p style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      Dear <strong style="color:#111827;">{{ $quotation->contact_name ?? $quotation->user?->name ?? 'Valued Customer' }}</strong>,
    </p>
    <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      Thank you for your interest. Please find your quotation from <strong>{{ $companyName }}</strong> below.
    </p>
  </td>
</tr>

<!-- Custom Email Message -->
@if(!empty($quotation->quotation_email_message))
<tr>
  <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#eef2ff; border-radius:14px; border:1px solid #e0e7ff;">
      <tr>
        <td style="padding:20px 24px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:22px; color:#4338ca; white-space:normal;">
                {!! nl2br(e($quotation->quotation_email_message)) !!}
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
@endif

<!-- Sender Company Info -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
      <tr>
        <td class="details-cell" style="padding:22px 24px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
              <td style="width:64px; vertical-align:middle;">
                @if($logoUrl)
                  <img src="{{ $logoUrl }}" alt="{{ $companyName }}"
                    style="display:block; width:56px; height:56px; border-radius:12px; object-fit:cover; border:1px solid #eef0f4;"
                    width="56" height="56">
                @else
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                    style="width:56px; height:56px; border-radius:12px; background-color:#eef2ff; border:1px solid #eef0f4;">
                    <tr>
                      <td style="text-align:center; vertical-align:middle; font-size:22px; line-height:56px;">🏢</td>
                    </tr>
                  </table>
                @endif
              </td>
              <td style="padding-left:16px; vertical-align:middle;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:17px; font-weight:700; color:#111827;">
                      {{ $companyName }}
                    </td>
                  </tr>
                  @if($addressLine)
                  <tr>
                    <td style="padding-top:4px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#6b7280;">
                      {{ $addressLine }}
                    </td>
                  </tr>
                  @endif
                </table>
              </td>
              <td style="width:80px; text-align:right; vertical-align:middle;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="right">
                  <tr>
                    <td style="background-color:#111827; border-radius:6px; padding:4px 12px;">
                      <span style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#ffffff;">{{ $senderLabel }}</span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:16px;">
            <tr>
              <td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td>
            </tr>
            @if(!empty($meta['vat_number']))
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:30%; vertical-align:top;">VAT Number</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $meta['vat_number'] }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            @endif
            @if(!empty($meta['website']))
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Website</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right; vertical-align:top; word-break:break-all;">
                <a href="{{ $meta['website'] }}" style="color:#4f46e5; text-decoration:none;">{{ $meta['website'] }}</a>
              </td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            @endif
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>

<!-- Quotation Details -->
<tr>
  <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
      <tr>
        <td class="details-cell" style="padding:22px 24px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
            <tr>
              <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                <span style="font-size:13px; line-height:26px;">📄</span>
              </td>
              <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                Quotation Details
              </td>
            </tr>
          </table>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Reference</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $quotation->quotation_number }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Issue Date</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $quotation->issue_date?->format('d M Y') ?? '—' }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Valid Until</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $quotation->valid_until?->format('d M Y') ?? '—' }}</td>
            </tr>
            @if($quotation->delivery_country)
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Delivery Country</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $quotation->delivery_country }}</td>
            </tr>
            @endif
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
<!-- Support -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 40px 40px;">
    <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#4b5563;">
      If you have any questions or require further information, please feel free to contact us. We would be happy to assist you.
    </p>
  </td>
</tr>

@include('emails.partials.footer')
