<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation {{ $quotation->quotation_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            background: #f4f7fb;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,.08);
        }

        .header {
            background: #0d6efd;
            color: #fff;
            padding: 35px;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            padding: 35px;
            line-height: 1.7;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            color: #111827;
        }

        .notes {
            background: #fff8e7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
        }

        .footer {
            padding: 25px 35px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #666;
        }

        @media only screen and (max-width:600px) {
            .content,
            .header,
            .footer {
                padding: 24px;
            }

            .row {
                display: block;
            }

            .label {
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>Quotation</h1>
        <p style="margin:10px 0 0;">
            Reference #{{ $quotation->quotation_number }}
        </p>
    </div>

    <div class="content">

        <p>Dear <strong>{{ $quotation->contact_name }}</strong>,</p>

        <p>
            Thank you for your interest in <strong>BWT</strong>.
            Please find your quotation attached to this email for your review.
        </p>

        <div class="card">

            <div class="row">
                <div class="label">Quotation Number</div>
                <div class="value">{{ $quotation->quotation_number }}</div>
            </div>

            <div class="row">
                <div class="label">Issue Date</div>
                <div class="value">
                    {{ $quotation->issue_date?->format('d M Y') }}
                </div>
            </div>

            <div class="row">
                <div class="label">Valid Until</div>
                <div class="value">
                    {{ $quotation->valid_until?->format('d M Y') }}
                </div>
            </div>

        </div>

        @if($quotation->notes)
            <div class="notes">
                <strong>Notes</strong>
                <p style="margin:10px 0 0;">
                    {{ $quotation->notes }}
                </p>
            </div>
        @endif

        <p>
            If you have any questions or require further information, please feel free to contact us. We would be happy to assist you.
        </p>

        <p style="margin-top:40px;">
            Kind regards,<br><br>

            <strong>{{ $quotation->user->name }}</strong><br>
            BWT
        </p>

    </div>

    <div class="footer">
        © {{ date('Y') }} BWT. All rights reserved.
    </div>

</div>

</body>
</html>
