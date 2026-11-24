{{-- Template sertifikat (DomPDF, A4 landscape). Variabel: $event --}}
@php $logo = public_path('mivent_assets/logoColor.png'); @endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; }
    body { margin: 0; font-family: DejaVu Sans, sans-serif; color: #111; }
    .frame { position: absolute; top: 24px; left: 24px; right: 24px; bottom: 24px; border: 6px double #40679F; }
    .inner { text-align: center; padding-top: 50px; }
    .h { font-size: 40px; letter-spacing: 6px; color: #40679F; font-weight: bold; margin: 16px 0 0; }
    .sub { font-size: 14px; letter-spacing: 3px; }
    .name { font-size: 30px; font-weight: bold; margin: 26px auto 6px; width: 60%; border-bottom: 1px solid #40679F; padding-bottom: 6px; }
    .sig { position: absolute; bottom: 70px; width: 220px; text-align: center; font-size: 11px; border-top: 1px solid #111; padding-top: 4px; }
</style>
</head>
<body>
<div class="frame">
    <div class="inner">
        @if (is_file($logo))<img src="{{ $logo }}" height="40">@endif
        <div class="h">SERTIFIKAT</div>
        <div class="sub">{{ $event->isPanitia() ? 'PANITIA' : 'PESERTA' }}</div>
        <p style="margin-top:28px;font-size:13px;">Diberikan kepada</p>
        <div class="name">[Nama Penerima]</div>
        <p style="font-size:13px;line-height:1.6;margin:10px 90px 0;">
            atas partisipasinya sebagai {{ $event->isPanitia() ? 'Panitia [Nama Sie]' : 'Peserta' }} dalam kegiatan
            <b>{{ $event->nama_kegiatan }}</b> yang diselenggarakan oleh {{ $event->organisasi?->nama ?? 'FMIPA Universitas Udayana' }}
            pada {{ $event->rentangTanggal() }}.
        </p>
    </div>
    <div class="sig" style="left:110px;">[Nama Ketua Panitia]<br>Ketua Panitia</div>
    <div class="sig" style="right:110px;">[Nama Ketua Umum]<br>Ketua {{ $event->organisasi?->singkatan ?? 'Ormawa' }}</div>
</div>
</body>
</html>
