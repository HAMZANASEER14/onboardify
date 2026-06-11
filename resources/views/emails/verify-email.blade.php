<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
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

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 16px 16px 0 0;
            padding: 22px 36px;
            text-align: center;
        }
        .logo-box {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            width: 34px; height: 34px;
            line-height: 34px;
            text-align: center;
            font-size: 17px;
            vertical-align: middle;
        }
        .logo-text {
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
            vertical-align: middle;
            margin-left: 9px;
            letter-spacing: -0.3px;
        }

        /* ── Body ── */
        .body {
            background: #ffffff;
            padding: 30px 36px 24px;
        }
        .icon-wrap {
            text-align: center;
            margin-bottom: 16px;
        }
        .icon-circle {
            display: inline-block;
            background: #dbeafe;
            border-radius: 50%;
            width: 52px; height: 52px;
            line-height: 52px;
            text-align: center;
            font-size: 24px;
        }
        .title {
            font-size: 19px;
            font-weight: 700;
            color: #111827;
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 13.5px;
            color: #6b7280;
            text-align: center;
            line-height: 1.6;
            margin-bottom: 18px;
        }
        .info-card {
            background: #f8faff;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 18px;
        }
        .info-card p {
            font-size: 12.5px;
            color: #4b5563;
            line-height: 1.5;
        }
        .btn-wrap {
            text-align: center;
            margin-bottom: 18px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            padding: 12px 36px;
            border-radius: 9px;
        }
        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin-bottom: 14px;
        }
        .fallback-label {
            font-size: 11.5px;
            color: #9ca3af;
            margin-bottom: 4px;
        }
        .fallback-url {
            font-size: 10.5px;
            word-break: break-all;
        }
        .fallback-url a { color: #2563eb; }

        /* ── Footer ── */
        .footer {
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            border-radius: 0 0 16px 16px;
            padding: 14px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.7;
        }

        /* ── Tablet ── */
        @media only screen and (min-width: 481px) and (max-width: 768px) {
            .container { max-width: 480px; }
            .header { padding: 20px 30px; }
            .body { padding: 26px 30px 22px; }
            .footer { padding: 12px 30px; }
        }

        /* ── Mobile ── */
        @media only screen and (max-width: 480px) {
            .wrapper { padding: 16px 12px; }
            .header { padding: 16px 20px; border-radius: 12px 12px 0 0; }
            .body { padding: 20px 20px 18px; }
            .footer { padding: 12px 20px; border-radius: 0 0 12px 12px; }
            .title { font-size: 17px; }
            .subtitle { font-size: 12.5px; }
            .btn { padding: 11px 28px; font-size: 13px; }
            .icon-circle { width: 46px; height: 46px; line-height: 46px; font-size: 20px; }
        }

        /* ── Large Desktop ── */
        @media only screen and (min-width: 1024px) {
            .container { max-width: 540px; }
            .body { padding: 32px 40px 26px; }
            .header { padding: 24px 40px; }
            .footer { padding: 16px 40px; }
            .title { font-size: 20px; }
            .subtitle { font-size: 14px; }
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
                <div class="icon-circle">✉️</div>
            </div>

            <p class="title">Verify your email address</p>
            <p class="subtitle">
                Thanks for signing up for
                <strong style="color:#111827;">Onboardify</strong>!<br>
                Please verify your email to activate your account.
            </p>

            <div class="info-card">
                <p>🔒 &nbsp; This link expires in <strong>60 minutes</strong>.
                If it expires, request a new one from the login page.</p>
            </div>

            <div class="btn-wrap">
                <a href="{{ $url }}" class="btn">✅ &nbsp; Verify Email Address</a>
            </div>

            <hr class="divider">

            <p class="fallback-label">If the button doesn't work, copy and paste this link:</p>
            <p class="fallback-url">
                <a href="{{ $url }}">{{ $url }}</a>
            </p>

        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>© {{ date('Y') }} Onboardify. All rights reserved.</p>
            <p>If you did not create an account, you can safely ignore this email.</p>
            <p>Onboardify · Sent with ❤️</p>
        </div>

    </div>
</div>

</body>
</html>