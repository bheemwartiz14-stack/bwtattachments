@php
    $appName = config('app.name');
    $appUrl = config('app.url');
    $supportEmail = config('site_settings.contact_email', config('mail.from.address'));
    $senderName = config('mail.from.name', $appName);
@endphp

@include('emails.partials.header', [
    'pageTitle' => 'Reseller Application Received',
    'previewText' => 'Thank you for applying to become a reseller. We will review your application shortly.',
    'heroHeading' => 'Reseller Application<br>Received',
    'heroSubtitle' => 'Thank you for your interest in becoming a reseller.',
])

<tr>
    <td class="fluid-padding" style="padding:36px 40px 6px 40px;">
        <p
            style="margin:0 0 16px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
            Dear <strong style="color:#111827;">{{ $application->contact_person }}</strong>,
        </p>
        <p
            style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:15px; line-height:24px; color:#4b5563;">
            Thank you for submitting your application to become a BWT Attachments reseller. We have received your
            application and our team will review it shortly.
        </p>
    </td>
</tr>

<tr>
    <td class="fluid-padding" style="padding:22px 40px 6px 40px;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
            style="background-color:#f9fafb; border-radius:14px;">
            <tr>
                <td class="details-cell" style="padding:22px 24px;">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                        style="margin-bottom:16px;">
                        <tr>
                            <td
                                style="width:26px; height:26px; background-color:#eef2ff; border-radius:8px; text-align:center; vertical-align:middle;">
                                <span style="font-size:13px; line-height:26px;">📝</span>
                            </td>
                            <td
                                style="padding-left:9px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13px; font-weight:700; letter-spacing:0.3px; color:#111827;">
                                Application Details
                            </td>
                        </tr>
                    </table>
                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; width:40%; vertical-align:top;">
                                Company</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">
                                {{ $application->company_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">
                                Contact Person</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">
                                {{ $application->contact_person }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top:1px solid #eef0f4; font-size:1px; line-height:1px;">
                                &nbsp;</td>
                        </tr>
                        <tr>
                            <td class="row-label"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; color:#9ca3af; vertical-align:top;">
                                Email</td>
                            <td class="row-value"
                                style="padding:8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:13.5px; font-weight:600; color:#1f2937; text-align:right; vertical-align:top;">
                                {{ $application->email }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td class="fluid-padding" style="padding:22px 40px 40px 40px;">
        <p
            style="margin:0 0 8px 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:22px; color:#4b5563;">
            We aim to process applications within 2-3 business days. If you have any questions in the meantime, please
            contact us at <a href="mailto:{{ $supportEmail }}"
                style="color:#4f46e5; text-decoration:none;">{{ $supportEmail }}</a>.
        </p>
    </td>
</tr>
@include('emails.partials.footer')
