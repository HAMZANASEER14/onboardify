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
        body {
            display: table;
            width: 100%;
            height: 100%;
        }
        .wrapper {
            display: table-cell;
            vertical-align: middle;
            padding: 24px 16px;
        }
        .container {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
        }

        /* ── Header (Green Theme) ── */
        .header {
            background: linear-gradient(135deg, #0B3D2E 0%, #2D6A4F 100%);
            border-radius: 16px 16px 0 0;
            padding: 28px 36px;
            text-align: center;
        }
        .logo-box {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            width: 38px; height: 38px;
            line-height: 38px;
            text-align: center;
            font-size: 18px;
            vertical-align: middle;
        }
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            vertical-align: middle;
            margin-left: 10px;
            letter-spacing: -0.3px;
        }

        /* ── Body ── */
        .body {
            background: #ffffff;
            padding: 36px 40px 32px;
        }
        .icon-wrap {
            text-align: center;
            margin-bottom: 20px;
        }
        .icon-circle {
            display: inline-block;
            background: linear-gradient(135deg, rgba(45,106,79,0.1) 0%, rgba(82,183,136,0.1) 100%);
            border-radius: 50%;
            width: 60px; height: 60px;
            line-height: 60px;
            text-align: center;
            font-size: 28px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            line-height: 1.7;
            margin-bottom: 24px;
          }
        .subtitle strong {
            color: #111827;
            font-weight: 600;
        }
        
        /* Waiver Card (Green Theme to match Onboardify) */
        .waiver-card {
            background: linear-gradient(135deg, rgba(45,106,79,0.05) 0%, rgba(82,183,136,0.05) 100%);
            border: 1px solid rgba(45,106,79,0.15);
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 24px;
            position: relative;
        }
        .waiver-label {
            font-size: 11px;
            font-weight: 700;
            color: #2D6A4F; 
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }
        .waiver-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 6px;
            line-height: 1.4;
        }
        .waiver-meta {
            font-size: 12px;
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            background: rgba(45,106,79,0.15);
            color: #2D6A4F;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            float: right;
            margin-top: -2px;
        }

        .desc {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 24px;
            text-align: center;
        }

        /* Button (Green Gradient) */
        .btn-wrap {
            text-align: center;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0B3D2E 0%, #2D6A4F 100%);
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 14px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(45,106,79,0.2);
            transition: all 0.2s;
        }
        .btn:hover {
            box-shadow: 0 6px 16px rgba(45,106,79,0.3);
            transform: translateY(-1px);
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 24px 0 16px;
        }
        .fallback-label {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 6px;
            text-align: center;
        }
        .fallback-url {
            font-size: 11px;
            word-break: break-all;
            text-align: center;
            background: #f9fafb;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .fallback-url a { 
            color: #2D6A4F; 
            text-decoration: none;
        }
        .fallback-url a:hover {
            text-decoration: underline;
        }

        /* ── Footer ── */
        .footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-radius: 0 0 16px 16px;
            padding: 20px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 11.5px;
            color: #9ca3af;
            line-height: 1.8;
        }

        /* ── Tablet ── */
        @media only screen and (min-width: 481px) and (max-width: 768px) {
            .container { max-width: 480px; }
            .header { padding: 24px 30px; }
            .body { padding: 30px 30px 26px; }
            .footer { padding: 16px 30px; }
        }

        /* ── Mobile ── */
        @media only screen and (max-width: 480px) {
            .wrapper { padding: 16px 12px; }
            .header { 
                padding: 20px 24px; 
                border-radius: 12px 12px 0 0; 
            }
            .logo-box { width: 34px; height: 34px; line-height: 34px; font-size: 16px; }
            .logo-text { font-size: 18px; margin-left: 8px; }
            .body { padding: 24px 20px 20px; }
            .footer { padding: 16px 20px; border-radius: 0 0 12px 12px; }
            .greeting { font-size: 19px; }
            .subtitle { font-size: 13px; margin-bottom: 20px; }
            .btn { padding: 12px 32px; font-size: 13.5px; width: 100%; }
            .icon-circle { width: 52px; height: 52px; line-height: 52px; font-size: 24px; }
            .waiver-card { padding: 14px; }
            .waiver-title { font-size: 15px; }
            .fallback-url { font-size: 10px; padding: 8px 12px; }
        }

        /* ── Large Desktop ── */
        @media only screen and (min-width: 1024px) {
            .container { max-width: 560px; }
            .body { padding: 40px 48px 32px; }
            .header { padding: 32px 48px; }
            .footer { padding: 20px 48px; }
            .greeting { font-size: 24px; }
            .subtitle { font-size: 15px; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <span class="logo-box">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" style="vertical-align: middle;">
                    <path fill="white" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <span class="logo-text">Onboardify</span>
        </div>

        {{-- Body --}}
        <div class="body">

            <div class="icon-wrap">
                <div class="icon-circle">✉️</div>
            </div>
            <p class="greeting">Hi {{ $waiverSend->client->name }},</p>
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
                     &nbsp; View &amp; Sign Waiver
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
            <p>Onboardify · Sent with greetings</p>
        </div>

    </div>
</div>

</body>
</html>