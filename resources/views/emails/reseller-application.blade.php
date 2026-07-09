@php
    $supportEmail = config('mail.from.admin_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $adminUrl = url('/admin');
    $appName = config('app.name');
@endphp

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>New Reseller Application</title>
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
  }
</style>
</head>
<body style="margin:0; padding:0; background-color:#eef0f5; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#eef0f5; opacity:0;">
  New reseller application received from {{ $application->company_name }}.
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
            <span style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; font-weight:800; color:#ffffff; line-height:40px;">{{ strtoupper(substr($appName, 0, 3)) }}</span>
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
          <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed); padding:44px 40px 40px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:20px;">
              <tr>
                <td style="width:56px; height:56px; background:rgba(255,255,255,0.15); border-radius:16px; text-align:center; vertical-align:middle;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:16px;">
                    <tr><td>
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M12 4V20M20 12H4" stroke="#ffffff" stroke-width="3" stroke-linecap="round"/>
                      </svg>
                    </td></tr>
                  </table>
                </td>
              </tr>
            </table>
            <span style="display:inline-block; padding:5px 14px; background:rgba(255,255,255,0.12); border-radius:999px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; color:rgba(255,255,255,0.8); margin-bottom:14px;">
              Reseller Program
            </span>
            <h1 class="hero-heading" style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:25px; line-height:32px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">
              New Application<br>Received
            </h1>
            <p style="margin:10px 0 0 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:rgba(255,255,255,0.7);">
              {{ $application->company_name }} wants to become a reseller.
            </p>
          </td>
        </tr>

        <!-- Intro -->
        <tr>
          <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
            <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
              A new reseller application has been submitted. Review the details below and take the appropriate action in the admin panel.
            </p>
          </td>
        </tr>

        <!-- Company Details -->
        <tr>
          <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">🏢</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Company Details
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Company Name</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->company_name }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Contact Person</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->contact_person }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">VAT Number</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->vat_number ?? '—' }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Chamber of Commerce</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->chamber_of_commerce ?? '—' }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Contact Information -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">📞</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Contact Information
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Email</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top; word-break:break-all;">
                        <a href="mailto:{{ $application->email }}" style="color:#4f46e5; text-decoration:none;">{{ $application->email }}</a>
                      </td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Telephone</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->telephone }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Website</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right; vertical-align:top;">
                        @if($application->website)
                          <a href="{{ $application->website }}" style="color:#4f46e5; text-decoration:none;">{{ $application->website }}</a>
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

        <!-- Address -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">📍</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Address
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Address</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->address }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Postal Code</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->postal_code }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Place</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->place }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Country</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $application->country }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        @if($application->additional_info)
        <!-- Additional Info -->
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
                          Additional Information
                        </p>
                        <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350f;">
                          {{ $application->additional_info }}
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

        <!-- CTA -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 6px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" class="cta-button" width="100%">
              <tr>
                <td align="center" style="border-radius:11px; background:linear-gradient(135deg,#4f46e5,#4338ca);">
                  <a href="{{ $adminUrl }}" target="_blank" style="display:inline-block; width:100%; padding:15px 32px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14.5px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:11px; box-sizing:border-box;">
                    View in Admin Panel &rarr;
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr><td style="padding:0 40px;"><div style="border-top:1px solid #eef0f4;"></div></td></tr>

        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#4b5563;">Best regards,</p>
            <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;">{{ $appName }} &mdash; Admin Notification</p>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td class="fluid-padding" style="padding:26px 20px 40px 20px; text-align:center;">
      <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11.5px; line-height:17px; color:#9ca3af;">
        This is an automated message from <a href="{{ $appUrl }}" style="color:#9ca3af; text-decoration:none;">{{ $appUrl }}</a>
      </p>
    </td>
  </tr>

</table>

<!--[if mso]></td></tr></table><![endif]-->
</center>
</body>
</html>
