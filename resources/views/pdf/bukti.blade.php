{{-- Bukti pendaftaran (DomPDF). Variabel: $r (Registration) --}}
@php
    $e = $r->event; $u = $r->user;
    $logo = public_path('mivent_assets/logoColor.png');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
    .head { border-bottom: 3px solid #40679F; padding-bottom: 10px; }
    .title { color: #40679F; font-size: 18px; font-weight: bold; }
    table.data { width: 100%; border-collapse: collapse; margin-top: 12px; }
    table.data td { padding: 6px 8px; border-bottom: 1px solid #EDF4FB; vertical-align: top; }
    table.data td.k { width: 32%; color: #40679F; font-weight: bold; }
    .badge { display: inline-block; padding: 3px 8px; border: 1px solid #40679F; border-radius: 4px; color: #40679F; font-weight: bold; }
    .foot { margin-top: 30px; font-size: 9px; color: #6b7280; }
</style>
</head>
<body>
<table class="head" width="100%"><tr>
    <td>@if (is_file($logo))<img src="{{ $logo }}" height="34">@endif</td>
    <td align="right"><div class="title">BUKTI PENDAFTARAN</div><div>No. MIV-{{ str_pad($r->id, 6, '0', STR_PAD_LEFT) }}</div></td>
</tr></table>

<h2 style="color:#40679F;margin:16px 0 0;">{{ $e->nama_kegiatan }}</h2>
<div>{{ $e->organisasi?->nama }} · {{ $e->rentangTanggal() }} · {{ $e->lokasi ?? '-' }}</div>

<table class="data">
    <tr><td class="k">Nama Lengkap</td><td>{{ $u->name }}</td></tr>
    <tr><td class="k">NIM</td><td>{{ $u->nim ?? '-' }}</td></tr>
    <tr><td class="k">Program Studi / Angkatan</td><td>{{ $u->jurusan?->nama ?? '-' }} / {{ $u->angkatan ?? '-' }}</td></tr>
    <tr><td class="k">Email</td><td>{{ $u->email }}</td></tr>
    <tr><td class="k">No. Telp</td><td>{{ $r->phone ?? $u->phone ?? '-' }}</td></tr>
    <tr><td class="k">Daftar Sebagai</td><td>{{ $e->isPanitia() ? 'Panitia' : 'Peserta' }}</td></tr>
    @if ($e->isPanitia())
        <tr><td class="k">Pilihan Sie 1</td><td>{{ $r->divisi?->nama ?? '-' }}</td></tr>
        <tr><td class="k">Pilihan Sie 2</td><td>{{ $r->divisi2?->nama ?? '-' }}</td></tr>
    @endif
    @if ($r->alasan)<tr><td class="k">Alasan</td><td>{{ $r->alasan }}</td></tr>@endif
    <tr><td class="k">Berkas Dilampirkan</td><td>{{ $r->files->pluck('label')->implode(', ') ?: '-' }}</td></tr>
    <tr><td class="k">Tanggal Daftar</td><td>{{ $r->created_at->translatedFormat('d F Y, H:i') }} WITA</td></tr>
    <tr><td class="k">Status</td><td><span class="badge">{{ $r->statusLabel() }}</span></td></tr>
</table>

<div class="foot">Dokumen ini dibuat otomatis oleh Mivent pada {{ now()->translatedFormat('d F Y H:i') }} WITA dan sah tanpa tanda tangan.</div>
</body>
</html>
