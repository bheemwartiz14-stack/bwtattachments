@php
    $introText = match ($userType) {
        'Wholesaler' => 'wholesaler account',
        'reseller', 'Reseller' => 'reseller account',
        'customer', 'Customer' => 'customer account',
        default => 'account',
    };

    $loginUrl = route('login');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($introText) }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color:#f3f4f6;">
    <tr>
        <td align="center" style="padding:32px 16px;">

            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0"
                style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden;">

                <!-- Content -->
                <tr>
                    <td style="
                        padding:32px 40px 24px 40px;
                        font-family:Arial, Helvetica, sans-serif;
                        font-size:15px;
                        line-height:24px;
                        color:#1f2937;
                    ">

                        <p style="margin:0 0 16px 0;">
                            Dear {{ $user->first_name ?? $user->name ?? 'User' }},
                        </p>

                        <p style="margin:0 0 16px 0;">
                            This is an automated generated email from bwtattachments.com
                        </p>

                        <p style="margin:0 0 16px 0;">
                            Your <strong>{{ $introText }}</strong> has been created successfully.
                        </p>

                        <p style="margin:0 0 16px 0;">
                            You can now access your account using the login details provided below.
                        </p>

                        <!-- Account Details -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="
                                margin:20px 0;
                                background-color:#f9fafb;
                                border:1px solid #e5e7eb;
                                border-radius:6px;
                            ">
                            <tr>
                                <td style="
                                    padding:16px 20px;
                                    font-family:Arial, Helvetica, sans-serif;
                                    font-size:14px;
                                    line-height:22px;
                                    color:#1f2937;
                                ">

                                    <p style="margin:0 0 8px 0;">
                                        <strong>Email:</strong> {{ $user->email }}
                                    </p>

                                    <p style="margin:0;">
                                        <strong>Password:</strong> {{ $password }}
                                    </p>

                                </td>
                            </tr>
                        </table>

                        <!-- Login Button -->
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0"
                            style="margin:24px 0;">
                            <tr>
                                <td align="center" style="border-radius:6px; background-color:#2563eb;">

                                    <a href="{{ $loginUrl }}"
                                        style="
                                            display:inline-block;
                                            padding:12px 24px;
                                            font-family:Arial, Helvetica, sans-serif;
                                            font-size:14px;
                                            font-weight:700;
                                            color:#ffffff;
                                            text-decoration:none;
                                            border-radius:6px;
                                        ">
                                        Access Your {{ ucfirst($introText) }} &rarr;
                                    </a>

                                </td>
                            </tr>
                        </table>
                        <p style="margin:24px 0 4px 0;">
                            Best regards,
                        </p>

                        <p style="margin:0; font-weight:700; color:#111827;">
                            BWT
                        </p>

                        <p style="margin:0;">
                            <a href="mailto:sales@bwtattachments.com"
                                style="color:#2563eb; text-decoration:none;">
                                sales@bwtattachments.com
                            </a>
                        </p>

                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="
                        padding:16px 40px;
                        background-color:#f9fafb;
                        border-top:1px solid #e5e7eb;
                        font-family:Arial, Helvetica, sans-serif;
                        font-size:12px;
                        line-height:18px;
                        color:#6b7280;
                        text-align:center;
                    ">
                        This is an automated email, please do not reply directly.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
