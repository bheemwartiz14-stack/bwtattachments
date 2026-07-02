@php
    $logoUrl = $user->userMeta?->getFirstMediaUrl('wholesale_client_logo');
    $clientName = $user->userMeta?->metadata['wholesale_client_name'] ?? 'Wholesale Client';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome — {{ $clientName }}</title>

<style>
    * { margin: 0; padding: 0; }

    body {
        background: #0b1220;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .wrapper {
        max-width: 560px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .card {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow:
            0 1px 3px 0 rgba(0,0,0,0.04),
            0 20px 50px -8px rgba(0,0,0,0.25);
    }

    /* ────── HEADER ────── */
    .header {
        background: linear-gradient(145deg, #0b1425 0%, #162033 50%, #1c293d 100%);
        padding: 40px 32px 36px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: -60%;
        left: -30%;
        width: 160%;
        height: 160%;
        background:
            radial-gradient(ellipse at 30% 20%, rgba(255,255,255,0.03) 0%, transparent 50%),
            radial-gradient(ellipse at 70% 80%, rgba(255,255,255,0.02) 0%, transparent 50%);
        pointer-events: none;
    }

    .header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 10%;
        right: 10%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.08), transparent);
    }

    .logo-ring {
        position: relative;
        display: inline-flex;
        margin-bottom: 16px;
    }

    .logo-ring::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.04));
        padding: 1px;
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }

    .logo {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
    }

    .logo-fallback {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
        color: #fff;
        letter-spacing: -0.02em;
    }

    .badge {
        display: inline-block;
        padding: 4px 14px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 999px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: rgba(255,255,255,0.7);
        margin-bottom: 10px;
    }

    h1 {
        font-size: 24px;
        font-weight: 600;
        color: #fff;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
    }

    .subtitle {
        font-size: 14px;
        color: rgba(255,255,255,0.55);
        font-weight: 400;
    }

    /* ────── CONTENT ────── */
    .content {
        padding: 32px;
        background: #f9fafb;
    }

    .greeting {
        font-size: 14px;
        color: #374151;
        line-height: 1.6;
        margin-bottom: 24px;
    }

    .greeting strong {
        color: #111827;
    }

    .credentials {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .cred-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 18px;
        font-size: 13px;
    }

    .cred-row:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }

    .cred-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-weight: 450;
    }

    .cred-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #d1d5db;
        flex-shrink: 0;
    }

    .cred-value {
        color: #111827;
        font-weight: 550;
        text-align: right;
        max-width: 55%;
        word-break: break-word;
    }

    .cred-value.password {
        font-family: 'SF Mono', 'Fira Code', 'Cascadia Code', monospace;
        font-size: 12px;
        letter-spacing: 0.01em;
        background: #f3f4f6;
        padding: 3px 10px;
        border-radius: 6px;
        color: #7c3aed;
    }

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
        margin: 24px 0;
    }

    .btn-wrap {
        text-align: center;
    }

    .btn {
        display: inline-block;
        background: linear-gradient(135deg, #111827, #1f2937);
        color: #fff;
        padding: 13px 34px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 550;
        text-decoration: none;
        letter-spacing: -0.01em;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
    }

    .btn:hover {
        background: linear-gradient(135deg, #1f2937, #374151);
    }

    .meta-note {
        font-size: 12px;
        color: #9ca3af;
        text-align: center;
        margin-top: 16px;
        line-height: 1.5;
    }

    /* ────── FOOTER ────── */
    .footer {
        padding: 20px 32px;
        text-align: center;
        font-size: 12px;
        color: #9ca3af;
        background: #ffffff;
        border-top: 1px solid #f3f4f6;
        line-height: 1.6;
    }

    .footer strong {
        color: #6b7280;
        font-weight: 500;
    }

    @media(max-width: 480px){
        .wrapper { padding: 24px 12px; }
        .content { padding: 24px 18px; }
        .header { padding: 32px 20px 28px; }
        .footer { padding: 16px 18px; }
        .cred-row { padding: 11px 14px; }
        h1 { font-size: 21px; }
    }
</style>
</head>

<body>
<div class="wrapper">

    <div class="card">

        <!-- HEADER -->
        <div class="header">
            <div class="logo-ring">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" class="logo" alt="logo">
                @else
                    <div class="logo-fallback">
                        {{ strtoupper(substr($clientName, 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="badge">{{ $clientName }}</div>
            <h1>You're in.</h1>
            <p class="subtitle">Your wholesale account is ready to go</p>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <p class="greeting">
                Hey <strong>{{ $user->name }}</strong>,
                your account has been set up. Sign in with the credentials below and you'll be browsing products in no time.
            </p>

            <div class="credentials">

                <div class="cred-row">
                    <span class="cred-label"><span class="cred-dot"></span> Email</span>
                    <span class="cred-value">{{ $user->email }}</span>
                </div>

                <div class="cred-row">
                    <span class="cred-label"><span class="cred-dot"></span> Username</span>
                    <span class="cred-value">{{ $user->username }}</span>
                </div>

                <div class="cred-row">
                    <span class="cred-label"><span class="cred-dot"></span> Password</span>
                    <span class="cred-value password">{{ $password }}</span>
                </div>

            </div>

            <div class="divider"></div>

            <div class="btn-wrap">
                <a href="{{ route('client.dashboard') }}" class="btn">Sign in to Portal &rarr;</a>
            </div>

            <p class="meta-note">
                You'll be asked to set a new password on first login.
            </p>

        </div>

        <!-- FOOTER -->
        <div class="footer">
            Sent by <strong>BWT</strong> &bull; This is an automated message<br>
            If you have any questions, reach out to your account administrator.
        </div>

    </div>

</div>
</body>
</html>
