<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: helvetica, sans-serif; color: #111827; font-size: 10pt; margin: 0; padding: 20px; }
        h1 { font-size: 18pt; font-weight: bold; text-align: center; color: #0B3D2E; margin-bottom: 10px; }
        h2 { font-size: 12pt; font-weight: bold; color: #0B3D2E; border-bottom: 2px solid #2D6A4F; padding-bottom: 5px; margin-top: 20px;}
        .meta-info { text-align: center; color: #40916c; font-size: 9pt; margin-bottom: 20px; }
        .field-label { font-weight: bold; font-size: 9pt; color: #2D6A4F; text-transform: uppercase; margin-top: 10px; }
        .field-value { font-size: 10pt; color: #111827; margin-bottom: 5px; }
        .signature-box { margin-top: 30px; text-align: center; border-top: 2px solid #2D6A4F; padding-top: 20px; }
        .signature-box img { max-width: 300px; height: auto; }
        .footer { margin-top: 50px; text-align: center; font-style: italic; font-size: 8pt; color: #52b788; }
    </style>
</head>
<body>
    <!-- Header & Meta Info -->
    <h1>{{ $waiver->title }}</h1>
    <div class="meta-info">
        Signed by: {{ $send->client_name ?? 'Client' }} ({{ $send->client_email ?? '' }})<br>
        Signed on: {{ $send->signed_at ? $send->signed_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }}
    </div>

    <!-- Form Responses -->
    <h2>Form Responses</h2>
    @foreach($fields as $field)
        @if($field['type'] === 'signature') @continue @endif
        <div class="field-label">{{ $field['label'] ?? '' }}</div>
        <div class="field-value">
            @php
                $value = $responses[$field['id'] ?? ''] ?? '—';
                if(is_array($value)) $value = implode(', ', $value);
            @endphp
            {{ $value }}
        </div>
    @endforeach

    <!-- Signature Section -->
    <div class="signature-box">
        <h2 style="border: none; text-align: center;">Signature</h2>
        @if($signature && str_starts_with($signature, 'data:image'))
            <img src="{{ $signature }}" alt="Signature" />
        @else
            <p style="color: red;">No signature provided.</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        This document was electronically signed via Onboardify · Token: {{ $send->token }}
    </div>
</body>
</html>