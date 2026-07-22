@php
    $meta = $user->userMeta?->metadata ?? [];

    if ($userType === 'wholesale') {
        $clientName   = $meta['wholesale_company_name'] ?? 'Wholesale Client';
        $accountLabel = 'Wholesale Client';
        $title        = 'Wholesale Account';
        $introText    = 'wholesale client account';
    } elseif ($userType === 'reseller') {
        $clientName   = $meta['company_name'] ?? $meta['retailer_client_name'] ?? 'Retailer Client';
        $accountLabel = 'Reseller';
        $title        = 'Reseller Account';
        $introText    = 'reseller client account';
    } else {
        $clientName   = $meta['company_name'] ?? $meta['custumber_client_name'] ?? 'Customer Client';
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

    $loginUrl = route('login');

    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $appName = config('app.name');
    $senderName = config('mail.from.name', $appName);
@endphp

@include('emails.partials.header', [
    'pageTitle' => $title . ' Confirmation',
    'previewText' => "Your " . lcfirst($title) . " is active — company details, account info, and secure login inside.",
    'heroHeading' => "Your {$title}<br>Is Now Active",
    'heroSubtitle' => 'Everything is set up and ready to go.',
])

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
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Company</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $clientName }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">VAT</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $meta['vat_number'] ?? '—' }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Address</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">
                {{ $addressLine ?: '—' }}
              </td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Website</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; text-align:right; vertical-align:top; word-break:break-all;">
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
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Contact</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $user->name }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Email</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top; word-break:break-all;">{{ $user->email }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Phone</td>
              <td class="row-value" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">{{ $user->phone ?? '—' }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td class="row-label" style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:middle;">Type</td>
              <td style="padding:8px 0; text-align:right; vertical-align:middle;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="right">
                  <tr>
                    <td style="background-color:#111827; border-radius:6px; padding:4px 12px;">
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
        <td class="details-cell" style="padding:22px 24px;">
          <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:16px;">
            <tr>
              <td style="width:26px; height:26px; background-color:#1e293b; border-radius:8px; text-align:center; vertical-align:middle;">
                <span style="font-size:13px; line-height:26px;">🔑</span>
              </td>
              <td style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#ffffff;">
                Login Credentials
              </td>
            </tr>
          </table>
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">Username</td>
              <td style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#ffffff; text-align:right; vertical-align:top;">{{ $user->username }}</td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #374151; font-size:1px; line-height:1px;">&nbsp;</td></tr>
            <tr>
              <td style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">Password</td>
              <td style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#ffffff; text-align:right; vertical-align:top;">{{ $password }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>

<!-- CTA -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 6px 40px; text-align:center;">
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" class="cta-button" width="100%">
      <tr>
        <td align="center" style="border-radius:10px; background-color:#111827;">
          <a href="{{ $loginUrl }}" target="_blank" style="display:inline-block; width:100%; padding:14px 32px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px; box-sizing:border-box; letter-spacing:-0.2px;">
            Access Your Account &rarr;
          </a>
        </td>
      </tr>
    </table>
  </td>
</tr>

<tr>
  <td class="fluid-padding" style="padding:16px 40px 6px 40px;">
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

@include('emails.partials.footer')
