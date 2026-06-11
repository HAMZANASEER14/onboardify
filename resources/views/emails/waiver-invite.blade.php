<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Your Waiver – Onboardify</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        html, body {
            height: 100%;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body { display:table; width:100%; height:100%; }
        .wrapper { display:table-cell; vertical-align:middle; padding:24px 16px; }
        .container { width:100%; max-width:520px; margin:0 auto; }

        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 16px 16px 0 0;
            padding: 22px 36px;
            text-align: center;
        }
        .logo-box {
            display:inline-block; background:rgba(255,255,255,0.15);
            border-radius:8px; width:34px; height:34px;
            line-height:34px; text-align:center;
            font-size:17px; vertical-align:middle;
        }
        .logo-text {
            font-size:19px; font-weight:700; color:#ffffff;
            vertical-align:middle; margin-left:9px;
        }

        .body { background:#ffffff; padding:30px 36px 24px; }

        .icon-wrap { text-align:center; margin-bottom:16px; }
        .icon-circle {
            display:inline-block; background:#dbeafe;
            border-radius:50%; width:52px; height:52px;
            line-height:52px; text-align:center; font-size:24px;
        }

        .greeting { font-size:19px; font-weight:700; color:#111827; text-align:center; margin-bottom:6px; }
        .subtitle  { font-size:13px; color:#6b7280; text-align:center; line-height:1.6; margin-bottom:18px; }

        .waiver-card {
            background:#f8faff; border:1px solid #dbeafe;
            border-radius:10px; padding:14px 18px;
            margin-bottom:18px;
        }
        .waiver-label {
            font-size:11px; font-weight:600; color:#2563eb;
            text-transform:uppercase; letter-spacing:0.8px; margin-bottom:4px;
        }
        .waiver-title { font-size:16px; font-weight:700; color:#111827; margin-bottom:4px; }
        .waiver-meta  { font-size:12px; color:#6b7280; }
        .badge {
            display:inline-block; background:#dbeafe; color:#1d4ed8;
            font-size:10px; font-weight:600; padding:3px 9px;
            border-radius:20px; float:right; margin-top:2px;
        }

        .desc { font-size:13px; color:#4b5563; line-height:1.6; margin-bottom:20px; }

        .btn-wrap { text-align:center; margin-bottom:20px; }
        .btn {
            display:inline-block;
            background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color:#ffffff; text-decoration:none;
            font-size:14px; font-weight:600;
            padding:12px 36px; border-radius:9px;
        }

        .divider { border:none; border-top:1px solid #e5e7eb; margin-bottom:14px; }
        .fallback-label { font-size:11px; color:#9ca3af; margin-bottom:4px; }
        .fallback-url   { font-size:10px; word-break:break-all; }
        .fallback-url a { color:#2563eb; }

        .footer {
            background:#f9fafb; border-top:1px solid #e5e7eb;
            border-radius:0 0 16px 16px;
            padding:14px 36px; text-align:center;
        }
        .footer p { font-size:11px; color:#9ca3af; line-height:1.7; }

        @media only screen and (max-width:480px) {
            .wrapper  { padding:12px 10px; }
            .header   { padding:16px 20px; border-radius:12px 12px 0 0; }
            .body     { padding:20px 20px 16px; }
            .footer   { padding:12px 20px; border-radius:0 0 12px 12px; }
            .greeting { font-size:17px; }
            .btn      { padding:11px 28px; font-size:13px; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <span class="logo-box">📋</span>
            <span class="logo-text">Onboardify</span>
        </div>

        {{-- Body --}}
        <div class="body">

            <div class="icon-wrap">
                <div class="icon-circle"></div>
            </div>

            <p class="greeting">Hi {{ $waiverSend->client->name }}, </p>
            <p class="subtitle">You have a document waiting for your signature.</p>

            {{-- Waiver Card --}}
            <div class="waiver-card">
                <div class="waiver-label">Document to Sign</div>
                <span class="badge">Action Required</span>
                <div class="waiver-title">{{ $waiverSend->waiver->title }}</div>
                <div class="waiver-meta">
                    Sent by Onboardify · {{ now()->format('M d, Y') }}
                </div>
            </div>

            <p class="desc">
                Please review and sign the document by clicking the button below.
                This only takes a few minutes.
            </p>

            {{-- CTA Button --}}
            <div class="btn-wrap">
                <a href="{{ route('waivers.sign', $waiverSend->token) }}" class="btn">
                    ✍️ &nbsp; View &amp; Sign Waiver
                </a>
            </div>

            <hr class="divider">

            <p class="fallback-label">If the button doesn't work, copy and paste this link:</p>
            <p class="fallback-url">
                <a href="{{ route('waivers.sign', $waiverSend->token) }}">
                    {{ route('waivers.sign', $waiverSend->token) }}
                </a>
            </p>

        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>© {{ date('Y') }} Onboardify. All rights reserved.</p>
            <p>If you did not expect this email, you can safely ignore it.</p>
            <p>Onboardify · Sent with ❤️</p>
        </div>

    </div>
</div>

</body>
</html>