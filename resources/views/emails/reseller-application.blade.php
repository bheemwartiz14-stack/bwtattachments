<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Reseller Application</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f1f5f9;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }
    .wrapper {
        width: 100%;
        background-color: #f1f5f9;
        padding: 48px 16px;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 8px 24px rgba(15,23,42,0.06);
    }
    .header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 40px 32px;
        text-align: center;
    }
    .header .badge {
        display: inline-block;
        background-color: rgba(255,255,255,0.15);
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 14px;
    }
    .header h1 {
        color: #ffffff;
        font-size: 22px;
        margin: 0;
        font-weight: 700;
    }
    .body {
        padding: 36px 32px;
    }
    .intro {
        font-size: 15px;
        color: #475569;
        margin: 0 0 28px;
        line-height: 1.6;
    }
    .card {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 4px 20px;
    }
    table.details {
        width: 100%;
        border-collapse: collapse;
    }
    table.details tr:not(:last-child) td {
        border-bottom: 1px solid #e2e8f0;
    }
    table.details td {
        padding: 14px 0;
        font-size: 14px;
        vertical-align: top;
    }
    table.details td.label {
        color: #64748b;
        font-weight: 600;
        width: 38%;
    }
    table.details td.value {
        color: #0f172a;
        font-weight: 500;
        text-align: right;
    }
    .btn-wrapper {
        text-align: center;
        margin-top: 32px;
    }
    .btn {
        display: inline-block;
        background-color: #4f46e5;
        color: #ffffff !important;
        text-decoration: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
    }
    .footer {
        text-align: center;
        padding: 24px 32px 32px;
        font-size: 12px;
        color: #94a3b8;
        border-top: 1px solid #f1f5f9;
    }
</style>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="header">
            <span class="badge">Reseller Program</span>
            <h1>New Application Received</h1>
        </div>
        <div class="body">
            <p class="intro">A new reseller application has been submitted. Review the details below.</p>
            <div class="card">
                <table class="details">
                    <tr>
                        <td class="label">Company name</td>
                        <td class="value">{{ $application->company_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Contact person</td>
                        <td class="value">{{ $application->contact_person }}</td>
                    </tr>
                    <tr>
                        <td class="label">Address</td>
                        <td class="value">{{ $application->address }}</td>
                    </tr>
                    <tr>
                        <td class="label">Postal code</td>
                        <td class="value">{{ $application->postal_code }}</td>
                    </tr>
                    <tr>
                        <td class="label">Place</td>
                        <td class="value">{{ $application->place }}</td>
                    </tr>
                    <tr>
                        <td class="label">Country</td>
                        <td class="value">{{ $application->country }}</td>
                    </tr>
                    <tr>
                        <td class="label">Telephone</td>
                        <td class="value">{{ $application->telephone }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td class="value">{{ $application->email }}</td>
                    </tr>
                    <tr>
                        <td class="label">Website</td>
                        <td class="value">{{ $application->website ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="btn-wrapper">
                <a href="{{ url('/admin') }}" class="btn">View in Admin</a>
            </div>
        </div>
        <div class="footer">
            This is an automated message, please do not reply directly.
        </div>
    </div>
</div>
</body>
</html>
