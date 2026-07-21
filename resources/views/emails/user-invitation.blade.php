@php
    $meta = $user->userMeta?->metadata ?? [];

    if ($userType === 'wholesale') {
        $clientName   = $meta['wholesale_company_name'] ?? 'Wholesale Client';
        $logoUrl      = $user->userMeta?->getFirstMediaUrl('wholesale_client_logo');
        $accountLabel = 'Wholesale Client';
        $title        = 'Wholesale Account';
        $introText    = 'wholesale client account';
    } elseif ($userType === 'reseller') {
        $clientName   = $meta['company_name'] ?? $meta['retailer_client_name'] ?? 'Retailer Client';
        $logoUrl      = $user->getFirstMediaUrl('customer_logo');
        $accountLabel = 'Reseller';
        $title        = 'Reseller Account';
        $introText    = 'Reseller client account';
    } else {
        $clientName   = $meta['company_name'] ?? $meta['custumber_client_name'] ?? 'Customer Client';
        $logoUrl      = $user->getFirstMediaUrl('customer_logo');
        $accountLabel = 'Customer Client';
        $title        = 'Customer Account';
        $introText    = 'customer account';
    }

    $addressParts = array_filter([
        $meta['address'] ?? null,
        $meta['postal_code'] ?? null,
        $meta['city'] ?? null,
        $meta['country'] ?? null,
    ]);
    $addressLine = implode(', ', $addressParts);

    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
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
  body { margin: 0; padding: 0; width: 100% !important; height: 100% !important; background-color: #f4f5f7; }
  @media screen and (max-width: 600px) {
    .email-container { width: 100% !important; }
    .fluid-padding { padding-left: 16px !important; padding-right: 16px !important; }
    .details-cell { padding: 16px !important; }
    .hero-heading { font-size: 22px !important; line-height: 30px !important; }
    .cta-button a { display: block !important; width: 100% !important; }
    .row-label { display: block !important; width: 100% !important; padding-bottom: 2px !important; }
    .row-value { display: block !important; width: 100% !important; padding-bottom: 10px !important; text-align: left !important; }
    .cred-grid td { display: block !important; width: 100% !important; }
    .cred-grid .cred-spacer { display: none !important; }
  }
</style>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#f4f5f7; opacity:0;">
  Your {{ lcfirst($title) }} is active — company details, account info, and secure login inside.
</div>

<center style="width:100%; background-color:#f4f5f7;">
<table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px; margin:0 auto;">

  <!-- Brand header -->
  <tr>
    <td style="padding:40px 0 24px 0; text-align:center;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
          <td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:22px; font-weight:800; color:#0f172a; letter-spacing:-0.5px;">
            {{ config('app.name') }}
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Main Card -->
  <tr>
    <td class="fluid-padding" style="padding:0 16px;">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);">
        <!-- Hero -->
        <tr>
          <td style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding:40px 40px 36px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-bottom:20px;">
              <tr>
                <td style="width:48px; height:48px; background:linear-gradient(135deg,#10b981,#059669); border-radius:14px; text-align:center; vertical-align:middle;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin-top:14px;">
                    <tr><td>
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">
                        <path d="M20 6L9 17L4 12" stroke="#ffffff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </td></tr>
                  </table>
                </td>
              </tr>
            </table>
            <h1 class="hero-heading" style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:24px; line-height:32px; font-weight:700; color:#ffffff; letter-spacing:-0.5px;">
              Your {{ $title }}<br>Is Now Active
            </h1>
            <p style="margin:8px 0 0 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:22px; color:#94a3b8;">
              Everything is set up and ready to go.
            </p>
          </td>
        </tr>

        <!-- Intro -->
        <tr>
          <td class="fluid-padding" style="padding:32px 40px 4px 40px;">
            <p style="margin:0 0 16px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#475569;">
              Dear <strong style="color:#0f172a;">{{ $user->name }}</strong>,
            </p>
            <p style="margin:0 0 8px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#475569;">
              We're pleased to let you know your {{ $introText }} has been successfully created — including access to our full product catalog, wholesale pricing, and business services.
            </p>
          </td>
        </tr>

        <!-- Company Information -->
        <tr>
          <td class="fluid-padding" style="padding:20px 40px 4px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
              <tr>
                <td class="details-cell" style="padding:20px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                    <tr>
                      <td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:600; letter-spacing:0.5px; color:#64748b; text-transform:uppercase;">
                        Company Information
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; width:38%; vertical-align:top;">Company</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top;">{{ $clientName }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:top;">VAT</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top;">{{ $meta['vat_number'] ?? '—' }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:top;">Address</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top;">
                        {{ $addressLine ?: '—' }}
                      </td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:top;">Website</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; text-align:right; vertical-align:top; word-break:break-all;">
                        @if(!empty($meta['website']))
                          <a href="{{ $meta['website'] }}" style="color:#2563eb; text-decoration:none;">{{ $meta['website'] }}</a>
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
          <td class="fluid-padding" style="padding:12px 40px 4px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;">
              <tr>
                <td class="details-cell" style="padding:20px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                    <tr>
                      <td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:600; letter-spacing:0.5px; color:#64748b; text-transform:uppercase;">
                        Account Information
                      </td>
                    </tr>
                  </table>
                  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; width:38%; vertical-align:top;">Contact</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top;">{{ $user->name }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:top;">Email</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top; word-break:break-all;">{{ $user->email }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:top;">Phone</td>
                      <td class="row-value" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; color:#0f172a; text-align:right; vertical-align:top;">{{ $user->phone ?? '—' }}</td>
                    </tr>
                    <tr><td colspan="2" style="border-top:1px solid #e2e8f0; font-size:1px; line-height:1px; height:0;">&nbsp;</td></tr>
                    <tr>
                      <td class="row-label" style="padding:7px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; color:#94a3b8; vertical-align:middle;">Type</td>
                      <td style="padding:7px 0; text-align:right; vertical-align:middle;">
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="right">
                          <tr>
                            <td style="background-color:#0f172a; border-radius:6px; padding:4px 12px;">
                              <span style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:700; color:#ffffff;">{{ $accountLabel }}</span>
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
          <td class="fluid-padding" style="padding:12px 40px 4px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#0f172a; border-radius:12px;">
              <tr>
                <td class="details-cell" style="padding:20px 24px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:14px;">
                    <tr>
                      <td style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; font-weight:600; letter-spacing:0.5px; color:#94a3b8; text-transform:uppercase;">
                        Login Credentials
                      </td>
                    </tr>
                  </table>

                  <table role="presentation" class="cred-grid" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="width:49%; vertical-align:top;">
                        <p style="margin:0 0 5px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Username</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#1e293b; border-radius:8px;">
                          <tr>
                            <td style="padding:10px 14px; font-family:'SF Mono', 'Courier New', Courier, monospace; font-size:14px; font-weight:600; color:#10b981;">
                              {{ $user->username }}
                            </td>
                          </tr>
                        </table>
                      </td>
                      <td class="cred-spacer" style="width:2%;">&nbsp;</td>
                      <td style="width:49%; vertical-align:top;">
                        <p style="margin:0 0 5px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:11px; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.5px;">Password</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#1e293b; border-radius:8px;">
                          <tr>
                            <td style="padding:10px 14px; font-family:'SF Mono', 'Courier New', Courier, monospace; font-size:14px; font-weight:600; color:#10b981;">
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
          <td class="fluid-padding" style="padding:24px 40px 4px 40px; text-align:center;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" class="cta-button" width="100%">
              <tr>
                <td align="center" style="border-radius:10px; background-color:#0f172a;">
                  <a href="{{ $loginUrl }}" target="_blank" style="display:inline-block; width:100%; padding:14px 32px; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px; box-sizing:border-box; letter-spacing:-0.2px;">
                    Access Your Account &rarr;
                  </a>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Security Notice -->
        <tr>
          <td class="fluid-padding" style="padding:16px 40px 4px 40px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#fef9e7; border-radius:10px; border:1px solid #fde68a;">
              <tr>
                <td style="padding:14px 18px;">
                  <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td style="width:18px; vertical-align:top; padding-top:1px; padding-right:10px;">
                        <span style="font-size:14px;">&#9888;&#65039;</span>
                      </td>
                      <td>
                        <p style="margin:0 0 4px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; color:#92400e;">
                          Security Notice
                        </p>
                        <p style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350f;">
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
          <td class="fluid-padding" style="padding:20px 40px 32px 40px;">
            <p style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#64748b;">
              If you need assistance or have any questions, our support team is always available to help.
            </p>
          </td>
        </tr>

        <tr><td style="padding:0 40px;"><div style="border-top:1px solid #e2e8f0;"></div></td></tr>
        <!-- Signature -->
        <tr>
          <td class="fluid-padding" style="padding:24px 40px 32px 40px;">
            <p style="margin:0 0 4px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#475569;">Best regards,</p>
            <p style="margin:0 0 2px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#94a3b8;">{{ config('app.name') }}</p>
            <p style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; color:#94a3b8;"><a href="mailto:{{ $supportEmail }}" style="color:#2563eb; text-decoration:none;">{{ $supportEmail }}</a></p>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <!-- Footer -->
  <tr>
    <td style="padding:24px 20px 40px 20px; text-align:center;">
      <p style="margin:0 0 6px 0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#94a3b8;">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
      </p>
      <p style="margin:0; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#94a3b8;">
        <a href="{{ $appUrl }}" style="color:#64748b; text-decoration:none;">{{ $appUrl }}</a>
      </p>
    </td>
  </tr>

</table>

<!--[if mso]></td></tr></table><![endif]-->
</center>
</body>
</html>
