@php
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
<title>New Contact Message</title>
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
  New contact message from {{ $contactMessage->name }} — {{ $contactMessage->subject }}.
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
                        <path d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5967 12 13.5967C12.3951 13.5967 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </td></tr>
                  </table>
                </td>
              </tr>
            </table>
            <h1 class="hero-heading" style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:25px; line-height:32px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">
              New Contact Message
            </h1>
            <p style="margin:10px 0 0 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#9ca3af;">
              You have received a new enquiry from your website.
            </p>
          </td>
        </tr>

        <!-- Intro -->
        <tr>
          <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
            <p style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
              Hello,
            </p>
            <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
              A new contact message has been submitted through the <strong>{{ $appName }}</strong> website. The details are below.
            </p>
          </td>
        </tr>

        <!-- Contact Details -->
        <tr>
          <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">📬</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Contact Details
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Name</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $contactMessage->name }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Email</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top; word-break:break-all;">{{ $contactMessage->email }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Subject</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $contactMessage->subject }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Message -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">💬</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Message
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="padding:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#374151;">
                        {{ $contactMessage->message }}
                      </td>
                    </tr>
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
              Please respond to this enquiry at your earliest convenience by replying to the sender directly at <a href="mailto:{{ $contactMessage->email }}" style="color:#4f46e5; text-decoration:underline;">{{ $contactMessage->email }}</a>.
            </p>
          </td>
        </tr>

        <tr><td style="padding:0 40px;"><div style="border-top:1px solid #eef0f4;"></div></td></tr>

        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#4b5563;">Best regards,</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111827;">{{ $senderName }}</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;">{{ $appName }}</p>
            <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;"><a href="mailto:{{ $supportEmail }}" style="color:#9ca3af; text-decoration:none;">{{ $supportEmail }}</a></p>
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
