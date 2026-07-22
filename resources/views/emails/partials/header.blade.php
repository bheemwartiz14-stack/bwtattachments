<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $pageTitle ?? '' }}</title>
<!--[if mso]>
<noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
<![endif]-->
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
  }
</style>
</head>
<body style="margin:0; padding:0; background-color:#eef0f5; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">

<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#eef0f5; opacity:0;">
  {{ $previewText ?? '' }}
</div>

<center style="width:100%; background-color:#eef0f5;">
<!--[if mso]>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" align="center"><tr><td>
<![endif]-->

<table role="presentation" class="email-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; margin:0 auto;">

  <!-- Brand header -->
  <tr>
    <td style="padding:36px 0 20px 0; text-align:center;">
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center">
        <tr>
          <td style="padding-left:11px; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:16px; font-weight:700; color:#111827; letter-spacing:-0.2px;">
            {{ $appName }}
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
            <h1 class="hero-heading" style="margin:0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:25px; line-height:32px; font-weight:800; color:#ffffff; letter-spacing:-0.5px;">
              {!! $heroHeading !!}
            </h1>
            <p style="margin:10px 0 0 0; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size:14px; line-height:20px; color:#9ca3af;">
              {{ $heroSubtitle }}
            </p>
          </td>
        </tr>
