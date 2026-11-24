{{-- Lihat Berkas pendaftar + lembar keputusan --}}
@php
    use App\Support\Files;
    $u = $registration->user;
    $berkasTahap = $event->tahap === 'seleksi_berkas';
@endphp
<x-admin-layout :title="'Berkas ' . $u->name">
    <a data-domino href="{{ route('manage.show', $event) }}" class="group inline-flex items-center gap-2 text-xs text-gray-100 transition hover:text-primary-200"><x-ui-icon name="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1" /> Kembali Detail Kelola Event</a>

    <div data-domino class="card mt-3 flex flex-wrap items-center justify-between gap-4 p-5">
        <div>
            <h1 class="text-2xl font-bold uppercase text-primary-200">{{ $u->name }}</h1>
            <p class="mt-1 text-sm">{{ $u->nim }} – {{ strtolower($u->jurusan?->nama ?? '-') }}{{ $u->angkatan ? "'" . substr((string) $u->angkatan, -2) : '' }} – {{ $registration->phone }}</p>
            <div class="mt-2 flex flex-wrap gap-2 text-sm font-semibold text-white">
                @if ($event->isPanitia())
                    <span class="rounded bg-primary-200 px-2 py-1">Pilihan 1 : {{ $registration->divisi?->nama ?? '-' }}</span>
                    @if ($registration->divisi2)<span class="rounded bg-primary-200 px-2 py-1">Pilihan 2 : {{ $registration->divisi2->nama }}</span>@endif
                @else
                    <span class="rounded bg-primary-200 px-2 py-1">Peserta</span>
                @endif
                <span class="rounded border px-2 py-0.5 {{ $registration->badgeClass() }}">{{ $registration->statusLabel() }}</span>
            </div>
        </div>
        <div class="flex gap-2 text-sm font-semibold">
            <a href="{{ route('manage.bukti', [$event, $registration]) }}" class="flex items-center gap-1 rounded-md bg-primary-150 px-3 py-2 text-white transition hover:bg-primary-200"><x-ui-icon name="file" class="h-4 w-4" /> Cetak Bukti Pendaftaran</a>
            <a href="{{ route('manage.zip', [$event, $registration]) }}" class="flex items-center gap-1 rounded-md border border-primary-150 px-3 py-2 text-primary-150 transition hover:bg-primary-150 hover:text-white"><x-ui-icon name="zip" class="h-4 w-4" /> Unduh Semua Dokumen (.zip)</a>
        </div>
    </div>

    @if ($registration->alasan)
        <div data-domino class="card mt-4 p-4 text-sm"><p class="font-semibold text-primary-200">Alasan Mendaftar</p><p class="mt-1">{{ $registration->alasan }}</p></div>
    @endif

    <div class="mt-6 grid grid-cols-[256px_1fr] items-start gap-6">
        <div class="space-y-4">
            <div data-domino data-file-switch="#berkas-viewer" class="card p-4">
                <h2 class="text-xl font-bold text-primary-200">Checklist Dokumen Mahasiswa</h2>
                <div class="mt-3 space-y-2">
                    @forelse ($registration->files as $f)
                        @php $url = Files::url($f->path); @endphp
                        @if ($url)
                            <button type="button" data-file-url="{{ $url }}" data-file-name="{{ $f->label }}" class="group flex w-full items-center gap-2 rounded-lg border border-primary-100 bg-white p-2 text-left transition hover:bg-blue-50">
                                <x-ui-icon name="file" class="h-6 w-6 shrink-0 text-primary-200" />
                                <span class="min-w-0 flex-1"><span class="block text-xs font-semibold text-primary-200">{{ $f->label }}</span><span class="block truncate text-xs">{{ basename($f->path) }}</span></span>
                                @if ($f->salah)<x-ui-icon name="alert" class="h-4 w-4 text-red-100" />@else<x-ui-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />@endif
                            </button>
                        @else
                            <div class="flex items-center gap-2 rounded-lg border border-red-100 bg-red-50 p-2 text-xs"><x-ui-icon name="file" class="h-5 w-5" /> {{ $f->label }} — tidak diisi</div>
                        @endif
                    @empty
                        <p class="text-xs text-gray-100">Pendaftar tidak mengunggah berkas.</p>
                    @endforelse
                </div>
            </div>

            @if ($bisaSeleksi)
                <form data-domino method="POST" action="{{ route('manage.keputusan', [$event, $registration]) }}" class="card p-4">
                    @csrf
                    <h2 class="text-xl font-bold text-primary-200">Lembar Keputusan & Seleksi</h2>
                    @if ($berkasTahap && $registration->files->isNotEmpty())
                        <p class="mt-2 text-xs">Centang berkas yang salah, lalu tulis catatannya.</p>
                        <div class="mt-2 space-y-2">
                            @foreach ($registration->files as $f)
                                <div class="group rounded-lg has-[:checked]:bg-white has-[:checked]:p-2 has-[:checked]:shadow-[0_0_8px_rgba(64,103,159,0.35)]">
                                    <label class="flex cursor-pointer items-start gap-2 text-sm"><input type="checkbox" name="salah[]" value="{{ $f->id }}" class="peer mt-0.5 accent-primary-200" @checked(in_array($f->id, old('salah', $registration->files->where('salah', true)->pluck('id')->all())))> {{ $f->label }}</label>
                                    <div class="mt-2 hidden rounded-lg border border-primary-50 p-2 group-has-[:checked]:block">
                                        <label for="catatan-{{ $f->id }}" class="text-sm">Deskripsi Singkat Kesalahan <span class="text-red-100">*</span></label>
                                        <input id="catatan-{{ $f->id }}" name="catatan[{{ $f->id }}]" value="{{ old('catatan.' . $f->id, $f->catatan) }}" maxlength="255" class="input mt-1 h-8 text-sm" placeholder="Contoh: berkas belum ada tanda tangan">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <x-field-error name="catatan.*" />
                    @else
                        <p class="mt-2 text-xs">Tentukan hasil {{ $berkasTahap ? 'seleksi berkas' : 'wawancara' }} untuk pendaftar ini.</p>
                    @endif
                    <div class="mt-4 grid grid-cols-2 gap-2 text-sm font-semibold">
                        <button name="keputusan" value="tolak" class="rounded-md border border-red-100 bg-red-50 py-2 text-red-100 transition hover:bg-red-100 hover:text-white">{{ $berkasTahap ? 'Tandai Berkas Salah' : 'Tidak Lolos' }}</button>
                        <button name="keputusan" value="terima" class="rounded-md border border-green-100 bg-green-50 py-2 text-green-150 transition hover:bg-green-100 hover:text-white">{{ $berkasTahap ? 'Tandai Berkas Sesuai' : 'Lolos' }}</button>
                    </div>
                    @if ($registration->keputusan)
                        <p class="mt-2 text-center text-xs">Keputusan sementara: <b>{{ $registration->keputusan === 'terima' ? 'Diterima' : 'Ditolak' }}</b></p>
                    @endif
                </form>
            @endif

            <div data-domino class="card flex items-center justify-between px-4 py-3 text-sm font-bold text-primary-200">
                @if ($prevId)<a href="{{ route('manage.registrant', [$event, $prevId]) }}" class="flex items-center transition hover:-translate-x-0.5"><x-ui-icon name="chevron-left" class="h-4 w-4" />Prev</a>@else<span class="flex items-center text-gray-100"><x-ui-icon name="chevron-left" class="h-4 w-4" />Prev</span>@endif
                <span class="text-xs font-normal text-black">{{ $posisi }} dari {{ $total }} Pendaftar</span>
                @if ($nextId)<a href="{{ route('manage.registrant', [$event, $nextId]) }}" class="flex items-center transition hover:translate-x-0.5">Next<x-ui-icon name="chevron-right" class="h-4 w-4" /></a>@else<span class="flex items-center text-gray-100">Next<x-ui-icon name="chevron-right" class="h-4 w-4" /></span>@endif
            </div>
        </div>

        <x-viewer data-domino id="berkas-viewer" />
    </div>
</x-admin-layout>
