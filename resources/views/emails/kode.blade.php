{{-- Email kode verifikasi (KodeMail: $kode, $judul, $nama) --}}
<!DOCTYPE html>
<html lang="id">
<body style="margin:0;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#111;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 0;">
    <tr><td align="center">
        <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:16px;box-shadow:0 0 15px rgba(64,103,159,.25);padding:32px;">
            <tr><td style="font-size:24px;font-weight:bold;color:#40679F;">Mivent</td></tr>
            <tr><td style="padding-top:16px;font-size:18px;font-weight:bold;color:#40679F;">{{ $judul }}</td></tr>
            <tr><td style="padding-top:12px;font-size:14px;line-height:1.6;">Halo {{ $nama }},<br>gunakan kode berikut untuk melanjutkan. Kode berlaku selama 15 menit.</td></tr>
            <tr><td align="center" style="padding:24px 0;">
                <span style="display:inline-block;letter-spacing:10px;font-size:32px;font-weight:bold;color:#40679F;background:#EDF4FB;border-radius:12px;padding:12px 24px;">{{ $kode }}</span>
            </td></tr>
            <tr><td style="font-size:12px;color:#6b7280;line-height:1.6;">Jika kamu tidak meminta kode ini, abaikan email ini. Jangan bagikan kode kepada siapa pun.</td></tr>
            <tr><td style="padding-top:24px;font-size:11px;color:#9ca3af;">© {{ date('Y') }} Mivent · FMIPA Universitas Udayana</td></tr>
        </table>
    </td></tr>
</table>
</body>
</html>
