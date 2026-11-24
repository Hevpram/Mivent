{{-- Detail Event Diikuti (Figma: Event Di Ikuti - Detail Seleksi Berkas 7:3808 / Berkas Salah 7:3992 / Update Berkas 7:4181 / Berkas Diperbarui 17:6089 / Hasil Seleksi 7:4404 / Sertifikat 7:4575) --}}
@php
    $r = $registration;
    $panitia = $event->isPanitia();
    $wa = fn ($no) => 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/\D/', '', (string) $no));
    $sertifikat = $r->status === 'diterima' ? $r->sertifikat() : null;
    $salah = $r->files->where('salah', true);
    $tabAwal = $sertifikat ? 'sertifikat' : (in_array($r->status, ['diterima', 'ditolak']) ? 'hasil' : 'proses');
    $pengumuman = $event->timelines->first(fn ($t) => str_contains(strtolower($t->nama_tahapan), 'pengumuman'));
    $kartu = 'flex w-[487px] flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]';
    $kotak = 'rounded-[10px] bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]';
    $tombol = 'flex w-full items-center justify-center gap-[18px] rounded-[10px] bg-primary-150 px-8 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-200';
    $dataDaftar = [
        'Nama Event' => strtoupper($event->nama_kegiatan),
        'Tanggal Pelaksanaan' => $event->rentangTanggal(),
        'Lokasi Event' => $event->lokasi,
        'Kategori SKP' => $event->skp?->nama ?? '-',
        'Penyelenggara' => $event->organisasi?->nama,
    ] + ($panitia ? ['Sie Pilihan 1' => $r->divisi?->nama ?? '-', 'Sie Pilihan 2' => $r->divisi2?->nama ?? '-'] : ['Peran' => 'Peserta'])
      + ['Status' => $r->statusLabel(), 'Waktu Submit' => $r->created_at->translatedFormat('l, d F Y')];
@endphp
<x-layout :title="$event->nama_kegiatan">
    <section class="relative isolate flex justify-center px-[155px] pb-[84px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex w-[1130px] flex-col items-start gap-3">
            {{-- prototype: Kembali Ke Event Di Ikuti -> Event Di Ikuti --}}
            <x-back-link data-domino :href="route('registrations.index')" label="Kembali Ke Event Di Ikuti" />

            <div class="flex w-full flex-col items-end gap-10">
                @include('partials.event-hero')

                <div class="flex w-full items-start gap-11">
                    {{-- Tentang Kegiatan --}}
                    <div data-domino class="flex w-[599px] shrink-0 flex-col gap-5 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('mivent_assets/icons/sec-tentang.svg') }}" alt="" class="h-[29.5px] w-[23.6px]">
                            <h2 class="text-2xl font-bold text-primary-200">Tentang Kegiatan</h2>
                        </div>
                        <div class="prose-mivent text-base text-black [&_p]:mb-4 [&_p:last-child]:mb-0">{!! clean($event->deskripsi) !!}</div>
                    </div>

                    <div class="flex flex-col gap-10">
                        {{-- Status Kepanitiaan --}}
                        <div data-domino class="{{ $kartu }}" data-tabs="{{ $tabAwal }}">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('mivent_assets/icons/sec-status.svg') }}" alt="" class="h-[26px] w-[23.4px]">
                                <h2 class="text-2xl font-bold text-primary-200">Status {{ $panitia ? 'Kepanitiaan' : 'Kepesertaan' }}</h2>
                            </div>
                            {{-- prototype: tab Proses Seleksi / Hasil Seleksi / Sertifikat --}}
                            <div class="flex w-fit gap-1.5 rounded-[10px] bg-gray-50 px-2 py-1">
                                <button type="button" data-tab="proses" class="tab-pill">{{ $panitia ? 'Proses Seleksi' : 'Pendaftaran' }}</button>
                                <button type="button" data-tab="hasil" class="tab-pill">{{ $panitia ? 'Hasil Seleksi' : 'Status' }}</button>
                                <button type="button" data-tab="sertifikat" class="tab-pill">Sertifikat</button>
                            </div>

                            {{-- Tab proses --}}
                            <div data-tab-pane="proses" class="hidden flex-col gap-5 [&:not(.hidden)]:flex">
                                @if ($salah->isNotEmpty())
                                    <div class="flex items-start gap-3 rounded-[10px] bg-red-50 p-3">
                                        <img src="{{ asset('mivent_assets/icons/alert-red.svg') }}" alt="" class="size-5">
                                        <div class="flex flex-1 flex-col gap-1">
                                            <p class="text-sm font-bold text-red-100">Berkas Anda Salah</p>
                                            <p class="text-xs text-black">Silakan klik tombol <b>“Update Kesalahan Berkas”</b> untuk memperbaiki dan memperbarui berkas Anda.</p>
                                        </div>
                                    </div>
                                @elseif (session('berkas_diperbarui'))
                                    <div class="flex items-start gap-3 rounded-[10px] bg-green-50 p-3">
                                        <img src="{{ asset('mivent_assets/icons/check-green.svg') }}" alt="" class="size-[19px]">
                                        <div class="flex flex-1 flex-col gap-1">
                                            <p class="text-sm font-bold text-green-150">Berkas Berhasil Diperbarui</p>
                                            <p class="text-xs text-black">Berkas berhasil diperbarui dan perubahan telah tersimpan dengan baik.</p>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex flex-col gap-4">
                                    <div class="{{ $kotak }} flex flex-col gap-3">
                                        @foreach ($dataDaftar as $k => $v)
                                            <div class="flex items-center justify-between gap-2 text-xs leading-[15px] text-black">
                                                <span class="w-[144px] shrink-0">{{ $k }} :</span>
                                                <b class="w-[261px] text-right font-semibold">{{ $v }}</b>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if ($salah->isEmpty() && ! session('berkas_diperbarui') && $pengumuman)
                                        <div class="{{ $kotak }} flex items-center gap-5">
                                            <img src="{{ asset('mivent_assets/icons/bell.svg') }}" alt="" class="h-[19.192px] w-[18.715px]">
                                            <p class="flex-1 text-xs leading-[15px] text-black">Pengumuman hasil akhir {{ $panitia ? 'kepanitiaan' : 'kepesertaan' }} akan diumumkan pada <b>{{ $pengumuman->tanggal_mulai?->translatedFormat('j F Y') }}</b> melalui website Mivent.</p>
                                        </div>
                                    @endif
                                </div>
                                @if ($salah->isNotEmpty())
                                    <a href="{{ route('registrations.pdf', $r) }}" class="group flex w-full items-center justify-center gap-[18px] rounded-[10px] border border-primary-150 px-8 py-2 text-base font-bold text-primary-150 shadow-[0_0_8px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-150 hover:text-white">
                                        <img src="{{ asset('mivent_assets/icons/printer-outline.svg') }}" alt="" class="h-[19.8px] w-[22px] transition group-hover:brightness-0 group-hover:invert"> Unduh Bukti Pendaftaran PDF
                                    </a>
                                    {{-- prototype: Update Kesalahan Berkas -> modal Update Berkas Anda --}}
                                    <button type="button" data-modal-open="perbaiki-berkas" class="{{ $tombol }}">Update Kesalahan Berkas</button>
                                @else
                                    <a href="{{ route('registrations.pdf', $r) }}" class="{{ $tombol }}"><img src="{{ asset('mivent_assets/icons/printer.svg') }}" alt="" class="h-[19.8px] w-[22px]"> Unduh Bukti Pendaftaran PDF</a>
                                @endif
                            </div>

                            {{-- Tab hasil --}}
                            <div data-tab-pane="hasil" class="hidden flex-col gap-6 [&:not(.hidden)]:flex">
                                @if ($r->status === 'diterima')
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col gap-2.5 rounded-[10px] bg-green-50 p-3">
                                            <div class="flex items-start gap-3">
                                                <img src="{{ asset('mivent_assets/icons/check-small.svg') }}" alt="" class="mt-0.5 size-[15px]">
                                                <div class="flex flex-1 flex-col gap-1">
                                                    <p class="text-sm font-bold text-green-150">{{ $panitia ? 'Pendaftaran Diterima' : 'Pendaftaran Terkonfirmasi' }}</p>
                                                    <p class="text-xs text-black">Selamat! Anda dinyatakan {{ $panitia ? 'lolos menjadi panitia pelaksana' : 'terdaftar sebagai peserta' }} {{ $event->nama_kegiatan }}{{ $panitia ? ' pada divisi:' : '.' }}</p>
                                                </div>
                                            </div>
                                            @if ($panitia)
                                                <div class="flex items-center justify-between rounded-[10px] bg-white px-3 py-2">
                                                    <div class="flex flex-col gap-1 text-xs leading-[15px] text-black"><span>Sie Kepanitiaan:</span><b class="font-semibold">{{ $r->divisiDiterima?->nama ?? $r->divisi?->nama }}</b></div>
                                                    <img src="{{ asset('mivent_assets/icons/check-circle-green.svg') }}" alt="" class="size-[23px]">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="{{ $kotak }} flex items-center gap-5">
                                            <img src="{{ asset('mivent_assets/icons/info-blue.svg') }}" alt="" class="size-5">
                                            <p class="flex-1 text-xs text-black">Harap segera bergabung ke <b>grup WhatsApp {{ $panitia ? 'panitia' : 'peserta' }}</b>, karena seluruh informasi akan disampaikan melalui grup tersebut. Untuk bergabung, silakan klik tombol di bawah ini.</p>
                                        </div>
                                    </div>
                                    <a href="{{ $wa($event->narahubung_telp) }}?text={{ rawurlencode('Halo, saya ' . auth()->user()->name . ' (' . auth()->user()->nim . ') ingin bergabung ke grup ' . $event->nama_kegiatan) }}" target="_blank" rel="noopener" class="{{ $tombol }}"><img src="{{ asset('mivent_assets/icons/wa-chat.svg') }}" alt="" class="size-[16.667px]"> Gabung Grup WhatsApp {{ $panitia ? 'Panitia' : 'Peserta' }}</a>
                                @elseif ($r->status === 'ditolak')
                                    <div class="flex items-start gap-3 rounded-[10px] bg-red-50 p-3">
                                        <img src="{{ asset('mivent_assets/icons/alert-red.svg') }}" alt="" class="size-5">
                                        <div class="flex flex-1 flex-col gap-1">
                                            <p class="text-sm font-bold text-red-100">Pendaftaran Belum Diterima</p>
                                            <p class="text-xs text-black">Terima kasih telah mendaftar. Mohon maaf, kamu belum lolos seleksi pada kegiatan ini. Tetap semangat dan coba event lainnya!</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('events.index') }}" class="{{ $tombol }}">Jelajahi Event Lain</a>
                                @else
                                    <div class="{{ $kotak }} flex items-center gap-5">
                                        <img src="{{ asset('mivent_assets/icons/bell.svg') }}" alt="" class="h-[19.192px] w-[18.715px]">
                                        <p class="flex-1 text-xs text-black">Hasil {{ $panitia ? 'seleksi' : 'konfirmasi' }} belum diumumkan. Hasil akan muncul di sini dan kamu akan menerima notifikasi.</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Tab sertifikat --}}
                            <div data-tab-pane="sertifikat" class="hidden flex-col gap-6 [&:not(.hidden)]:flex">
                                @if ($sertifikat)
                                    <div class="{{ $kotak }} flex flex-col gap-2">
                                        <p class="text-xs font-bold leading-[15px] text-black">Diberikan Kepada :</p>
                                        <div class="flex flex-col gap-3">
                                            @foreach (['Nama Lengkap' => auth()->user()->name, 'Nama Event' => strtoupper($event->nama_kegiatan), 'Tanggal Pelaksanaan' => $event->rentangTanggal(), 'Lokasi Event' => $event->lokasi, 'Kategori SKP' => $event->skp?->nama ?? '-', 'Penyelenggara' => $event->organisasi?->nama, 'Sebagai' => $panitia ? 'Panitia ' . ($r->divisiDiterima?->nama ?? $r->divisi?->nama) : 'Peserta'] as $k => $v)
                                                <div class="flex items-center justify-between gap-2 text-xs leading-[15px] text-black"><span class="w-[96px] shrink-0">{{ $k }} :</span><b class="text-right font-semibold">{{ $v }}</b></div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <a href="{{ \App\Support\Files::url($sertifikat->file_sertif) }}" target="_blank" rel="noopener" download class="{{ $tombol }}"><img src="{{ asset('mivent_assets/icons/download-white.svg') }}" alt="" class="size-4"> Unduh E-Sertifikat Resmi (PDF)</a>
                                @else
                                    <div class="{{ $kotak }} flex items-center gap-5">
                                        <img src="{{ asset('mivent_assets/icons/bell.svg') }}" alt="" class="h-[19.192px] w-[18.715px]">
                                        <p class="flex-1 text-xs text-black">Sertifikat akan tersedia setelah acara selesai dan diunggah oleh penyelenggara.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pusat Bantuan & Narahubung --}}
                        <div data-domino class="{{ $kartu }}">
                            <div class="flex items-center gap-4">
                                <img src="{{ asset('mivent_assets/icons/sec-bantuan.svg') }}" alt="" class="h-[24.6px] w-[27.333px]">
                                <h2 class="text-2xl font-bold text-primary-200">Pusat Bantuan &amp; Narahubung</h2>
                            </div>
                            <p class="text-sm text-black">Jika ada pertanyaan lebih lanjut atau kendala, bisa langsung menghubungi Contact Person berikut:</p>
                            <div class="flex flex-col gap-2">
                                @foreach ([[$event->narahubung_nama, $event->narahubung_telp], [$event->narahubung2_nama, $event->narahubung2_telp]] as [$nama, $telp])
                                    @if ($telp)
                                        <a href="{{ $wa($telp) }}" target="_blank" rel="noopener" class="flex items-center gap-[18px] rounded-[10px] bg-gray-50 px-3 py-1 text-sm font-medium text-black transition-all duration-300 hover:bg-blue-50 hover:text-primary-200">
                                            <img src="{{ asset('mivent_assets/icons/wa-phone.svg') }}" alt="" class="size-[34px]"> {{ $telp }} ( {{ $nama }} )
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal sukses setelah mendaftar (Figma: Daftar Event 4 7:5212 — Konfirmasi Registrasi). prototype: Ke Portal Event -> Event --}}
    <div data-modal="sukses" @if (session('registered')) data-modal-autoopen @endif class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/20 p-4 backdrop-blur-[2px]" role="dialog" aria-modal="true" aria-labelledby="sukses-judul">
        <div data-modal-panel class="flex w-[559px] flex-col gap-6 rounded-[20px] border border-gray-100 bg-primary-50 px-6 py-5 drop-shadow-[0_0_8.55px_rgba(64,103,159,0.75)]">
            <div class="flex flex-col items-center gap-3">
                <img src="{{ asset('mivent_assets/icons/success-big.svg') }}" alt="" class="size-[71px]">
                <span class="rounded-[10px] bg-green-50 px-3 py-1 text-xs font-bold text-green-150">Pendaftaran Berhasil Terkirim</span>
                <p id="sukses-judul" class="text-base font-bold text-black">Konfirmasi Registrasi {{ $panitia ? 'Panitia' : 'Peserta' }}</p>
                <p class="w-[454px] text-center text-sm text-black">Terima kasih, berkas pendaftaran Anda untuk kegiatan {{ $event->nama_kegiatan }} telah masuk ke dalam sistem {{ $panitia ? 'seleksi' : 'pendaftaran' }}.</p>
                <div class="grid w-full grid-cols-[300px_175px] gap-3 rounded-[10px] border border-gray-100 bg-white px-3 py-2 text-xs leading-[15px] text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]">
                    <div class="flex flex-col gap-1"><span>Pendaftar :</span><b class="font-semibold">{{ auth()->user()->name }}</b></div>
                    <div class="flex flex-col gap-1"><span>Waktu Submit :</span><b class="font-semibold">{{ $r->created_at->translatedFormat('l, d F Y') }}</b></div>
                    <div class="flex flex-col gap-1"><span>{{ $panitia ? 'Posisi Pilihan :' : 'Peran :' }}</span>
                        @if ($panitia)<ol class="list-decimal font-semibold [&>li]:ms-[18px]"><li>{{ $r->divisi?->nama }}</li>@if ($r->divisi2)<li>{{ $r->divisi2->nama }}</li>@endif</ol>@else<b class="font-semibold">Peserta</b>@endif
                    </div>
                    <div class="flex flex-col gap-1"><span>Status Berkas :</span><b class="font-semibold text-green-150">Menunggu {{ $panitia ? 'Seleksi' : 'Konfirmasi' }}</b></div>
                </div>
            </div>
            <div class="flex h-10 items-start justify-between">
                <a href="{{ route('registrations.pdf', $r) }}" class="btn-outline-nav group"><img src="{{ asset('mivent_assets/icons/printer-outline.svg') }}" alt="" class="h-[19.8px] w-[22px]"> Unduh Bukti PDF</a>
                <a href="{{ route('events.index') }}" class="btn-fill-nav group">Ke Portal Event <span class="flex h-[14px] w-[23px] items-center justify-center"><img src="{{ asset('mivent_assets/icons/nav-arrow-right.svg') }}" alt="" class="h-[23px] w-[14px] max-w-none rotate-90 transition-transform duration-300 group-hover:translate-x-1"></span></a>
            </div>
        </div>
    </div>


    {{-- Modal Update Berkas Anda (Figma: Event Di Ikuti - Update Berkas 7:4181) --}}
    @if ($salah->isNotEmpty())
        <div data-modal="perbaiki-berkas" @if ($errors->any()) data-modal-autoopen @endif class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/20 p-4 backdrop-blur-[2px]" role="dialog" aria-modal="true" aria-labelledby="perbaiki-judul">
            <form data-modal-panel method="POST" action="{{ route('registrations.berkas', $r) }}" enctype="multipart/form-data" data-once class="flex max-h-[92vh] w-[489px] flex-col items-center gap-4 overflow-y-auto rounded-[10px] bg-primary-50 py-4 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
                @csrf
                <div class="flex w-full flex-col gap-3">
                    <p id="perbaiki-judul" class="px-7 text-xl font-bold leading-7 text-primary-200">Update Berkas Anda</p>
                    <div class="h-px w-full bg-gray-100/50"></div>
                </div>
                <div class="flex w-full flex-col items-center gap-2.5 px-[30px]">
                    <p class="w-full text-sm text-black">Berkas anda pada {{ $salah->pluck('label')->implode(', ') }} terdapat kesalahan. Silahkan upload ulang berkas ulang.</p>
                    @foreach ($salah as $f)
                        @php $fid = 'ganti-' . $f->id; @endphp
                        <div class="flex w-[433px] flex-col gap-2 rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]">
                            @if ($f->catatan)
                                <div class="flex items-center gap-3"><img src="{{ asset('mivent_assets/icons/alert-small.svg') }}" alt="" class="size-[13px]"><p class="text-xs text-red-100">{{ $f->catatan }}</p></div>
                            @endif
                            <div class="flex items-center gap-3">
                                <img src="{{ asset('mivent_assets/icons/file-doc.svg') }}" alt="" class="size-8">
                                <div class="flex min-w-0 flex-col gap-0.5">
                                    <p class="text-sm font-bold leading-[18px] text-primary-200">{{ $f->label }}</p>
                                    <p id="{{ $fid }}-label" data-empty="{{ $f->path ? basename($f->path) : 'Belum ada file' }}" class="truncate text-xs leading-[15px] text-black">{{ $f->path ? basename($f->path) : 'Belum ada file' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start justify-between">
                                @if ($f->path)
                                    <a href="{{ \App\Support\Files::url($f->path) }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-[6px] border border-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-primary-200 transition-all duration-300 hover:bg-blue-50"><img src="{{ asset('mivent_assets/icons/eye-outline.svg') }}" alt="" class="h-[10.469px] w-4"> Lihat Berkas</a>
                                @else
                                    <span></span>
                                @endif
                                <label class="flex cursor-pointer items-center gap-2 rounded-[6px] border border-white bg-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-white transition-all duration-300 hover:bg-primary-150">
                                    <img src="{{ asset('mivent_assets/icons/swap-white.svg') }}" alt="" class="h-[10.667px] w-[13.333px]"> Ganti Berkas
                                    <input type="file" name="berkas[{{ $f->id }}]" accept=".pdf,.jpg,.jpeg,.png" data-file-label="#{{ $fid }}-label" class="sr-only" required>
                                </label>
                            </div>
                            <x-field-error :name="'berkas.' . $f->id" />
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="flex w-[430px] items-center justify-center rounded-[10px] bg-primary-150 px-8 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-200">Update Kesalahan Berkas</button>
            </form>
        </div>
    @endif
</x-layout>
