@php
    $adminUrl = url('/admin');
    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
    $appUrl = config('app.url');
    $appName = config('app.name');
    $senderName = config('mail.from.name', $appName);
@endphp

@include('emails.partials.header', [
    'pageTitle' => 'New Reseller Application',
    'previewText' => "New reseller application received from {$application->company_name}.",
    'heroHeading' => 'New Reseller Application',
    'heroSubtitle' => "{$application->company_name} wants to become a reseller.",
])

<!-- Intro -->
<tr>
  <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
    <p style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      Hello,
    </p>
    <p style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
      A new reseller application has been submitted through the <strong>{{ $appName }}</strong> website. The details are below.
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

<!-- Support -->
<tr>
  <td class="fluid-padding" style="padding:22px 40px 40px 40px;">
    <p style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#4b5563;">
      Please review this application in the admin panel and take the appropriate action.
    </p>
  </td>
</tr>

@include('emails.partials.footer')
