<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Team Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f3f4f6; padding: 20px; margin: 0;">

    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">

        {{-- Header --}}
        <div style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F); padding: 32px 24px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 700;">Welcome to the Team!</h1>
        </div>

        {{-- Content --}}
        <div style="padding: 32px 24px;">

            <p style="font-size: 16px; margin-bottom: 16px;">Hi there,</p>

            <p style="font-size: 16px; margin-bottom: 24px;">
                <strong>{{ $adminName }}</strong> has invited you to join their company on <strong>Onboardify</strong> to complete your onboarding, sign waivers, and manage your tasks.
            </p>

           {{-- Company Code Box --}}
<div style="background:linear-gradient(135deg, rgba(45,106,79,0.05) 0%, rgba(82,183,136,0.05) 100%); padding:24px; border-radius:12px; text-align:center; margin-bottom:24px; border:1px dashed rgba(45,106,79,0.3);">

    <p style="margin:0 0 12px; font-size:13px; color:#2D6A4F; text-transform:uppercase; letter-spacing:1px; font-weight:600;">
        Your Company Code
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;">
        <tr>
            <td style="background:#ffffff; border:2px solid #2D6A4F; padding:18px 36px; border-radius:8px; font-family:monospace; font-size:30px; font-weight:bold; color:#0B3D2E; letter-spacing:8px; text-align:center;">
                {{ $companyCode }}
            </td>
        </tr>
    </table>

    <p style="margin:16px 0 4px; font-size:13px; color:#4b5563;">
        <strong>Use your mouse to highlight the code above, then press:</strong>
    </p>
    <p style="margin:0; font-size:12px; color:#6b7280;">
        Windows: <strong>Ctrl + C</strong> &nbsp;|&nbsp; Mac: <strong>⌘ + C</strong>
    </p>

</div>

            <p style="font-size: 16px; margin-bottom: 24px;">
                Click the button below to create your account and get started:
            </p>

            {{-- Call to Action Button --}}
            <div style="text-align: center; margin-bottom: 32px;">
                <a href="{{ $inviteLink }}" style="display: inline-block; background: linear-gradient(135deg, #0B3D2E, #2D6A4F); color: #ffffff; padding: 14px 36px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; box-shadow: 0 4px 6px rgba(45,106,79,0.2);">
                    Complete My Onboarding
                </a>
            </div>

            {{-- Fallback Link --}}
            <p style="font-size: 13px; color: #9ca3af; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 20px;">
                If the button above doesn't work, copy and paste this link into your browser:<br>
                <a href="{{ $inviteLink }}" style="color: #2D6A4F; word-break: break-all; text-decoration: none;">{{ $inviteLink }}</a>
            </p>

        </div>

        {{-- Footer --}}
        <div style="background: #f9fafb; padding: 20px 24px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                If you didn't expect this email, you can safely ignore it.
            </p>
        </div>

    </div>

</body>
</html>