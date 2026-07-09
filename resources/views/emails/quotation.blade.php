@php
    $meta = $quotation->reseller?->userMeta?->metadata ?? [];

    $companyName = $meta['wholesale_company_name'] ?? $meta['company_name'] ?? '—';
    $supportEmail = config('mail.from.admin_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $appName = config('app.name');
    $senderName = config('mail.from.name', $appName);
    $brandInitial = strtoupper(substr($appName, 0, 1));
@endphp

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Quotation {{ $quotation->quotation_number }}</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
<style>
  body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
  body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #eef0f5; }

  @media screen and (max-width: 600px) {
    .email-container { width: 100% !important; }
    .fluid-padding { padding-left: 18px !important; padding-right: 18px !important; }
    .details-cell { padding: 18px !important; }
    .hero-heading { font-size: 22px !important; line-height: 30px !important; }
    .cta-button a { display: block !important; width: 100% !important; }
    .row-label { display: block !important; width: 100% !important; padding-bottom: 2px !important; }
    .row-value { display: block !important; width: 100% !important; text-align: left !important; }
    .cred-grid td { display: block !important; width: 100% !important; }
    .cred-grid .cred-spacer { display: none !important; }
  }
</style>
</head>
<body style="margin:0; padding:0; background-color:#eef0f5; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#eef0f5; opacity:0;">
  Quotation {{ $quotation->quotation_number }} from {{ $appName }} — details inside.
</div>

<center style="width:100%; background-color:#eef0f5;">
<!--[if mso]>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" align="center"><tr><td>
<![endif]-->

<table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; margin:0 auto;">

  <!-- Brand header -->
  <tr>
    <td style="padding:36px 0 20px 0; text-align:center;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
          <td style="width:40px; height:40px; background:#111827; border-radius:11px; text-align:center; vertical-align:middle;">
            <span style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; font-weight:800; color:#ffffff; line-height:40px;">{{ $brandInitial }}</span>
          </td>
          <td style="padding-left:11px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; font-weight:700; color:#111827; letter-spacing:-0.2px;">
            {{ $appName }}
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Main Card -->
  <tr>
    <td class="fluid-padding" style="padding:0 18px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:20px; overflow:hidden; box-shadow:0 4px 24px rgba(17,24,39,0.06), 0 1px 2px rgba(17,24,39,0.04); border:1px solid #eef0f5;">

        <!-- Hero -->
        <tr>
          <td style="background-color:#111827; padding:44px 40px 40px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:20px;">
              <tr>
                <td style="width:56px; height:56px; background:linear-gradient(135deg,#34d399,#10b981); border-radius:16px; text-align:center; vertical-align:middle;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:17px;">
                    <tr><td>
                      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M9 12H15M9 16H15M17 21H7C5.895 21 5 20.105 5 19V5C5 3.895 5.895 3 7 3H12.586C12.851 3 13.105 3.105 13.293 3.293L18.707 8.707C18.895 8.895 19 9.149 19 9.414V19C19 20.105 18.105 21 17 21Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </td></tr>
                  </table>
                </td>
              </tr>
            </table>
            <h1 class="hero-heading" style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:25px; line-height:32px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">
              Quotation<br>{{ $quotation->quotation_number }}
            </h1>
            <p style="margin:10px 0 0 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#9ca3af;">
              Please find your quotation attached to this email.
            </p>
          </td>
        </tr>

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
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ number_format((float) $quotation->grand_total, 2) }}</td>
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
        <!-- Notes -->
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

        <tr><td style="padding:0 40px;"><div style="border-top:1px solid #eef0f4;"></div></td></tr>

        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#4b5563;">Kind regards,</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111827;">{{ $quotation->user?->name ?? $senderName }}</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;">{{ $companyName }}</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td class="fluid-padding" style="padding:26px 20px 40px 20px; text-align:center;">
      <p style="margin:0 0 6px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11.5px; line-height:17px; color:#9ca3af;">
        &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
      </p>
      <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11.5px; line-height:17px; color:#9ca3af;">
        <a href="{{ $appUrl }}" style="color:#9ca3af; text-decoration:none;">{{ $appUrl }}</a>
      </p>
    </td>
  </tr>

</table>

<!--[if mso]></td></tr></table><![endif]-->
</center>
</body>
</html>
