<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>

    <style>
        body{
            margin:0;
            padding:40px 20px;
            background:#f4f7fb;
            font-family:Arial, Helvetica, sans-serif;
            color:#333;
        }

        .container{
            max-width:650px;
            margin:0 auto;
            background:#fff;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .header{
            background:#2563eb;
            color:#fff;
            padding:30px;
        }

        .header h1{
            margin:0;
            font-size:28px;
        }

        .content{
            padding:30px;
        }

        .card{
            background:#f8fafc;
            border:1px solid #e5e7eb;
            border-radius:10px;
            padding:20px;
            margin-top:20px;
        }

        .row{
            margin-bottom:18px;
        }

        .label{
            display:block;
            font-size:13px;
            font-weight:bold;
            color:#6b7280;
            margin-bottom:6px;
            text-transform:uppercase;
            letter-spacing:.5px;
        }

        .value{
            font-size:16px;
            color:#111827;
            word-break:break-word;
        }

        .message-box{
            background:#fff;
            border:1px solid #dbe3ec;
            border-left:4px solid #2563eb;
            border-radius:8px;
            padding:18px;
            white-space:pre-line;
            line-height:1.7;
        }

        .footer{
            padding:20px 30px;
            text-align:center;
            font-size:13px;
            color:#6b7280;
            background:#f8fafc;
            border-top:1px solid #e5e7eb;
        }

        @media(max-width:600px){
            .header,
            .content,
            .footer{
                padding:20px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>New Contact Message</h1>
        <p style="margin:8px 0 0;">
            You have received a new enquiry from your website.
        </p>
    </div>

    <div class="content">

        <div class="card">

            <div class="row">
                <span class="label">Name</span>
                <div class="value">{{ $contactMessage->name }}</div>
            </div>

            <div class="row">
                <span class="label">Email</span>
                <div class="value">{{ $contactMessage->email }}</div>
            </div>

            <div class="row">
                <span class="label">Subject</span>
                <div class="value">{{ $contactMessage->subject }}</div>
            </div>

            <div class="row">
                <span class="label">Message</span>

                <div class="message-box">
                    {{ $contactMessage->message }}
                </div>
            </div>

        </div>

    </div>

    <div class="footer">
        © {{ date('Y') }} BWT. This email was generated automatically.
    </div>

</div>

</body>
</html>
