@php
    $meta = $quotation->reseller?->userMeta?->metadata ?? [];
    $companyName = $meta['wholesale_company_name'] ?? $meta['company_name'] ?? '—';
    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $appName = config('app.name');
    $senderName = config('mail.from.name', $appName);
@endphp

@include('emails.partials.header', [
    'pageTitle' => 'Quotation ' . $quotation->quotation_number,
    'previewText' => "Quotation {$quotation->quotation_number} from {$appName} — details inside.",
    'heroHeading' => "Quotation<br>{$quotation->quotation_number}",
    'heroSubtitle' => 'Please find your quotation attached to this email.',
])

<!-- Intro -->
<tr>
  <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
    <p style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      Dear <strong style="color:#111827;">{{ $quotation->contact_name ?? $quotation->user?->name ?? 'Valued Customer' }}</strong>,
    </p>
    <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      Thank you for your interest in <strong>{{ $appName }}</strong>. Please find your quotation attached to this email for your review.
    </p>
  </td>
</tr>

<!-- Quotation Details -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
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
            @if($quotation->grand_total)
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Total Amount</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $quotation->grand_total }}</td>
            </tr>
            @endif
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

<!-- Company Information -->
<tr>
  <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
      <tr>
        <td class="details-cell" style="padding:22px 24px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
            <tr>
              <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                <span style="font-size:13px; line-height:26px;">🏢</span>
              </td>
              <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                Company Information
              </td>
            </tr>
          </table>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Company Name</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $companyName }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">VAT Number</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $meta['vat_number'] ?? '—' }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Business Address</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">
                {{ $meta['address'] ?? '' }}{{ !empty($meta['postal_code']) ? ', ' . $meta['postal_code'] : '' }}{{ !empty($meta['city']) ? ', ' . $meta['city'] : '' }}{{ empty($meta['address']) && empty($meta['postal_code']) && empty($meta['city']) ? '—' : '' }}
              </td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Website</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right; vertical-align:top;">
                @if(!empty($meta['website']))
                  <a href="{{ $meta['website'] }}" style="color:#4f46e5; text-decoration:none;">{{ $meta['website'] }}</a>
                @else
                  —
                @endif
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>

@if($quotation->notes)
<tr>
  <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fffbeb; border-radius:12px;">
      <tr>
        <td style="padding:16px 18px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="width:20px; vertical-align:top; padding-top:1px; padding-right:10px;">
                <span style="font-size:15px;">📝</span>
              </td>
              <td>
                <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:700; color:#92400e;">
                  Notes
                </p>
                <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350f;">
                  {{ $quotation->notes }}
                </p>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
@endif

<!-- Support -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 40px 40px;">
    <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#4b5563;">
      If you have any questions or require further information, please feel free to contact us. We would be happy to assist you.
    </p>
  </td>
</tr>

@include('emails.partials.footer')
