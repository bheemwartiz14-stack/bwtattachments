@php
    $meta = $user->userMeta?->metadata ?? [];

    if ($userType === 'wholesale') {
        $clientName   = $meta['wholesale_company_name'] ?? 'Wholesale Client';
        $logoUrl      = $user->userMeta?->getFirstMediaUrl('wholesale_client_logo');
        $accountLabel = 'Wholesale Client';
        $title        = 'Wholesale Account';
        $introText    = 'wholesale client account';
    } elseif ($userType === 'retailer') {
        $clientName   = $meta['company_name'] ?? $meta['retailer_client_name'] ?? 'Retailer Client';
        $logoUrl      = $user->getFirstMediaUrl('customer_logo');
        $accountLabel = 'Retailer Client';
        $title        = 'Retailer Account';
        $introText    = 'retailer client account';
    } else {
        $clientName   = $meta['company_name'] ?? $meta['custumber_client_name'] ?? 'Customer Client';
        $logoUrl      = $user->getFirstMediaUrl('customer_logo');
        $accountLabel = 'Customer Client';
        $title        = 'Customer Account';
        $introText    = 'customer account';
    }

    $supportEmail = config('mail.from.admin_email', config('mail.from.address'));
    $appUrl       = config('app.url');
    $loginUrl     = route('login');
    $senderName   = config('mail.from.name', config('app.name'));
@endphp

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $title }} Confirmation</title>
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
  Your {{ lcfirst($title) }} is active — company details, account info, and secure login inside.
</div>

<center style="width:100%; background-color:#eef0f5;">
<table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; margin:0 auto;">

  <!-- Brand header -->
  <tr>
    <td style="padding:36px 0 20px 0; text-align:center;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
          <td style="width:40px; height:40px; background:#111827; border-radius:11px; text-align:center; vertical-align:middle;">
            <span style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:18px; font-weight:800; color:#ffffff; line-height:40px;">{{ strtoupper(substr(config('app.name'), 0, 3)) }}</span>
          </td>
          <td style="padding-left:11px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; font-weight:700; color:#111827; letter-spacing:-0.2px;">
            {{ config('app.name') }}
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
                        <path d="M20 6L9 17L4 12" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </td></tr>
                  </table>
                </td>
              </tr>
            </table>
            <h1 class="hero-heading" style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:25px; line-height:32px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">
              Your {{ $title }}<br>Is Now Active
            </h1>
            <p style="margin:10px 0 0 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#9ca3af;">
              Everything is set up and ready to go.
            </p>
          </td>
        </tr>

        <!-- Intro -->
        <tr>
          <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
            <p style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
              Dear <strong style="color:#111827;">{{ $user->name }}</strong>,
            </p>
            <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
              We're pleased to let you know your {{ $introText }} has been successfully created — including access to our full product catalog, wholesale pricing, and business services.
            </p>
          </td>
        </tr>

        <!-- Company Information -->
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
                        Company Information
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Company Name</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $clientName }}</td>
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
                        @if($userType === 'wholesale')
                          {{ $meta['address'] ?? '—' }}
                        @else
                          {{ $meta['address'] ?? '' }}{{ !empty($meta['postal_code']) ? ', ' . $meta['postal_code'] : '' }}{{ !empty($meta['city']) ? ', ' . $meta['city'] : '' }}{{ empty($meta['address']) && empty($meta['postal_code']) && empty($meta['city']) ? '—' : '' }}
                        @endif
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

        <!-- Account Information -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9fafb; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">👤</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                        Account Information
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Name</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $user->name }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Email Address</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top; word-break:break-all;">{{ $user->email }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Phone Number</td>
                      <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $user->phone ?? '—' }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:middle;">Account Type</td>
                      <td style="padding:8px 0; text-align:right; vertical-align:middle;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="right">
                          <tr>
                            <td style="background-color:#111827; border-radius:20px; padding:5px 13px;">
                              <span style="font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#ffffff;">{{ $accountLabel }}</span>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Login Credentials -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#111827; border-radius:14px;">
              <tr>
                <td class="details-cell" style="padding:22px 24px 24px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
                    <tr>
                      <td style="width:26px; height:26px; background-color:rgba(255,255,255,0.1); border-radius:8px; text-align:center; vertical-align:middle;">
                        <span style="font-size:13px; line-height:26px;">🔒</span>
                      </td>
                      <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#ffffff;">
                        Login Credentials
                      </td>
                    </tr>
                  </table>

                  <table role="presentation" class="cred-grid" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="width:49%; vertical-align:top;">
                        <p style="margin:0 0 6px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.4px;">Username</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#1f2937; border-radius:9px;">
                          <tr>
                            <td style="padding:12px 14px; font-family:'Courier New', Courier, monospace; font-size:14px; font-weight:600; color:#34d399;">
                              {{ $user->username }}
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td class="cred-spacer" style="width:2%;">&nbsp;</td>
                      <td style="width:49%; vertical-align:top;">
                        <p style="margin:0 0 6px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11px; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.4px;">Password</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#1f2937; border-radius:9px;">
                          <tr>
                            <td style="padding:12px 14px; font-family:'Courier New', Courier, monospace; font-size:14px; font-weight:600; color:#34d399;">
                              {{ $password }}
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                  <div style="display:none; max-height:0; line-height:0; font-size:0;" class="mobile-only-space">&nbsp;</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- CTA -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 6px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" class="cta-button" width="100%">
              <tr>
                <td align="center" style="border-radius:11px; background:linear-gradient(135deg,#4f46e5,#4338ca);">
                  <a href="{{ $loginUrl }}" target="_blank" style="display:inline-block; width:100%; padding:15px 32px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14.5px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:11px; box-sizing:border-box;">
                    Click Here to Access Your Account &rarr;
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Security Notice -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 6px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fffbeb; border-radius:12px;">
              <tr>
                <td style="padding:16px 18px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="width:20px; vertical-align:top; padding-top:1px; padding-right:10px;">
                        <span style="font-size:15px;">&#9888;&#65039;</span>
                      </td>
                      <td>
                        <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:700; color:#92400e;">
                          Security Notice
                        </p>
                        <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350f;">
                          For your account security, please change your password after your first login. Do not share your login credentials with anyone.
                        </p>
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
              If you need assistance or have any questions, our support team is always available to help.
            </p>
          </td>
        </tr>

        <tr><td style="padding:0 40px;"><div style="border-top:1px solid #eef0f4;"></div></td></tr>

        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:26px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#4b5563;">Best regards,</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:700; color:#111827;">{{ $senderName }}</p>
            <p style="margin:0 0 2px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#9ca3af;">{{ config('app.name') }}</p>
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
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
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
