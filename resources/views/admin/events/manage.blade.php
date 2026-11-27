{{-- Kelola Event: pendaftar, seleksi bertahap, dan sertifikat --}}
@php
    use App\Support\Files;
    $tahap = $event->tahap;
    $isSeleksi = in_array($tahap, ['seleksi_berkas', 'seleksi_wawancara'], true);
    $fase = $tahap === 'pendaftaran' ? 'on' : ($isSeleksi ? 'sel' : 'done');
    $diterima = $registrations->where('status', 'diterima')->values();
    $prodi = fn ($u) => trim(($u->jurusan?->nama ?? '-') . ($u->angkatan ? " '" . substr((string) $u->angkatan, -2) : ''));
    $sie = fn ($r) => $r->divisiDiterima?->nama ?? $r->divisi?->nama ?? '-';
    $nextLabel = match ($tahap) {
        'pendaftaran' => 'Tutup Pendaftaran & Mulai Seleksi',
        'seleksi_berkas' => $event->isPanitia() ? 'Lanjut ke Wawancara' : 'Selesaikan Acara',
        'seleksi_wawancara' => 'Selesaikan Acara',
        default => null,
    };
    $editable = $event->status === 'disetujui';
@endphp
<x-admin-layout :title="'Kelola ' . $event->nama_kegiatan">
    {{-- Figma: Kelola Event - Sedang Berlangsung 7:13313 / Proses Seleksi 7:13452, 7:13650 / Acara Selesai 7:13855.
         prototype: Kembali Kelola Event -> Kelola Event; Lihat Berkas -> Lihat Berkas pendaftar (7:14000) --}}
    <div class="mx-auto flex w-[1043px] max-w-full flex-col gap-10">
    <div class="flex flex-col gap-3">
        <a data-domino href="{{ route('manage.index') }}" class="group flex w-fit items-center gap-3 text-sm text-gray-100 transition-colors duration-300 hover:text-primary-200">
            <img src="{{ asset('mivent_assets/icons/m-back.svg') }}" alt="" class="h-[14.7px] w-[19px] transition-transform duration-300 group-hover:-translate-x-1"> Kembali Kelola Event
        </a>

        <div data-domino class="flex min-h-[193px] w-full flex-col gap-6 rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex items-center gap-6">
                <img src="{{ $event->posterUrl() }}" alt="Poster {{ $event->nama_kegiatan }}" class="size-[89px] shrink-0 rounded-[10px] object-cover">
                <div class="flex w-[721px] max-w-full flex-col gap-1">
                    <h1 class="text-2xl font-bold uppercase text-primary-200">{{ $event->nama_kegiatan }}</h1>
                    <p class="text-sm text-black">Kelola pendaftar dan alur seleksi {{ $event->isPanitia() ? 'kepanitiaan' : 'peserta' }} ormawa secara terstruktur.</p>
                </div>
            </div>
            <div class="flex items-center justify-between gap-3">
                <div class="flex gap-1.5 rounded-[10px] bg-gray-50 px-2 py-1" aria-label="Tahap event">
                    @foreach (['on' => ['Sedang Berlangsung', 'm-tab-on', 'size-[15px]'], 'sel' => ['Proses Seleksi', 'm-tab-sel', 'h-[12.3px] w-[15px]'], 'done' => ['Acara Selesai', 'm-tab-done', 'size-[18.333px]']] as $k => [$n, $ic, $sz])
                        <span @class(['flex items-center justify-center gap-2.5 whitespace-nowrap px-4 py-1 text-base', 'rounded-[10px] bg-white text-black' => $fase === $k, 'rounded-[5px] text-gray-100' => $fase !== $k]) @if ($fase === $k) aria-current="step" @endif>
                            <img src="{{ asset('mivent_assets/icons/' . $ic . ($fase === $k ? '-on' : '-off') . '.svg') }}" alt="" class="{{ $sz }}"> {{ $n }}
                        </span>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex h-10 items-center gap-2 whitespace-nowrap rounded-[6px] border border-gray-100 bg-white p-2 text-base font-semibold text-primary-200 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]">
                        <img src="{{ asset('mivent_assets/icons/m-user.svg') }}" alt="" class="size-[13.333px]">
                        {{ $fase === 'done' ? 'Total ' . ($event->isPanitia() ? 'Panitia' : 'Peserta') . ': ' . $diterima->count() : 'Total Terdaftar: ' . $registrations->count() }} Mahasiswa</span>
                </div>
            </div>
        </div>
    </div>

    @if ($editable && $nextLabel)
        <div data-domino class="-mt-5 flex justify-end">
            <form method="POST" action="{{ route('manage.tahap', $event) }}" data-confirm="Lanjutkan tahap event? Tindakan ini tidak dapat dibatalkan.">
                @csrf
                <button class="flex h-10 items-center gap-2 rounded-[6px] bg-primary-200 px-4 text-sm font-semibold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-150"><x-ui-icon name="arrow-right" class="h-4 w-4" /> {{ $nextLabel }}</button>
            </form>
        </div>
    @endif

    @unless ($editable)
        <div data-domino class="rounded-[10px] border border-yellow-100 bg-yellow-50 p-4 text-sm text-yellow-100">Event ini belum tayang ({{ $event->statusLabel() }}). Pengelolaan pendaftar tersedia setelah event disetujui Admin FMIPA.</div>
    @endunless

    @if ($fase === 'on')
        <div data-domino class="flex flex-col gap-5 overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]">
            <div class="flex items-center gap-6 bg-primary-200 px-3 py-3 text-center text-base font-bold leading-5 text-white">
                <p class="w-[250px] shrink-0">Nama Lengkap Mahasiswa</p>
                <p class="w-[100px] shrink-0">NIM</p>
                <p class="w-[120px] shrink-0">Nomor Telp</p>
                @if ($event->isPanitia())<p class="w-[157px] shrink-0">Sie Pilihan 1</p><p class="w-[157px] shrink-0">Sie Pilihan 2</p>@endif
                <p class="w-[120px] shrink-0">Berkas</p>
            </div>
            <div class="flex flex-col">
                @forelse ($registrations as $r)
                    <div class="flex flex-col gap-1 transition-colors duration-300 hover:bg-blue-50">
                        <div class="flex items-center gap-6 px-3 py-2 text-sm leading-5 text-black">
                            <div class="flex w-[250px] shrink-0 flex-col"><p class="truncate font-bold">{{ $r->user->name }}</p><p class="text-xs">{{ $prodi($r->user) }}</p></div>
                            <p class="w-[100px] shrink-0 text-center">{{ $r->user->nim }}</p>
                            <p class="w-[120px] shrink-0 text-center">{{ $r->phone }}</p>
                            @if ($event->isPanitia())
                                <p class="w-[157px] shrink-0 text-center">{{ $r->divisi?->nama ?? '-' }}</p>
                                <p class="w-[157px] shrink-0 text-center">{{ $r->divisi2?->nama ?? '-' }}</p>
                            @endif
                            <a href="{{ route('manage.registrant', [$event, $r]) }}" class="flex h-[22px] w-[120px] shrink-0 items-center justify-center gap-1 rounded-[3px] bg-primary-200 text-sm font-medium text-white transition-all duration-300 hover:bg-primary-150">
                                <img src="{{ asset('mivent_assets/icons/m-file-white.svg') }}" alt="" class="h-[12.5px] w-2.5"> Lihat Berkas
                            </a>
                        </div>
                        <div class="h-px w-full bg-primary-200"></div>
                    </div>
                @empty
                    <p class="px-4 py-10 text-center text-sm text-gray-100">Belum ada pendaftar.</p>
                @endforelse
            </div>
            <div class="flex items-center justify-between px-6">
                <p class="text-sm font-semibold leading-5 text-black">Show {{ $registrations->count() ? 1 : 0 }} to {{ $registrations->count() }} entries</p>
                <a href="{{ route('manage.export', $event) }}" class="flex items-center gap-2 text-sm font-semibold text-primary-200 underline transition-colors duration-300 hover:text-primary-150">Download Data Pendaftar</a>
            </div>
        </div>
    @elseif ($fase === 'sel')
        <div data-domino class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex gap-1 rounded-lg bg-white p-1 text-xs shadow-sm">
                <span @class(['rounded-md px-4 py-1.5', 'bg-primary-200 font-semibold text-white' => $tahap === 'seleksi_berkas', 'text-gray-100' => $tahap !== 'seleksi_berkas'])>1. Seleksi Berkas</span>
                @if ($event->isPanitia())<span @class(['rounded-md px-4 py-1.5', 'bg-primary-200 font-semibold text-white' => $tahap === 'seleksi_wawancara', 'text-gray-100' => $tahap !== 'seleksi_wawancara'])>2. Wawancara</span>@endif
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <a href="{{ route('manage.export', $event) }}" class="flex items-center gap-1 rounded-md bg-white px-3 py-2 text-primary-200 shadow-sm transition hover:bg-primary-200 hover:text-white"><x-ui-icon name="download" class="h-4 w-4" /> Download Data</a>
                @foreach (['terima_semua' => ['Terima Semua', 'border-green-100 bg-green-50 text-green-150 hover:bg-green-100 hover:text-white'], 'tolak_semua' => ['Tolak Semua', 'border-red-100 bg-red-50 text-red-100 hover:bg-red-100 hover:text-white'], 'reset' => ['Reset', 'border-primary-100 bg-white text-primary-200 hover:bg-primary-200 hover:text-white']] as $aksi => [$n, $cls])
                    <form method="POST" action="{{ route('manage.seleksi', $event) }}">@csrf<input type="hidden" name="aksi" value="{{ $aksi }}"><button class="rounded-md border px-3 py-2 transition {{ $cls }}">{{ $n }}</button></form>
                @endforeach
                <form method="POST" action="{{ route('manage.seleksi', $event) }}" data-confirm="Simpan hasil seleksi dan kirim notifikasi ke pendaftar?">@csrf<input type="hidden" name="aksi" value="simpan"><button class="flex items-center gap-1 rounded-md bg-primary-200 px-3 py-2 text-white transition hover:bg-primary-150"><x-ui-icon name="check" class="h-4 w-4" /> Simpan Hasil</button></form>
            </div>
        </div>
        <div data-domino class="overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]">
            <table class="w-full text-center text-sm text-black">
                <thead class="bg-primary-200 text-base font-bold leading-5 text-white"><tr><th class="px-3 py-3">Nama Lengkap Mahasiswa</th><th>NIM</th><th>Nomor Telp</th>@if ($event->isPanitia())<th>Pilihan Sie</th>@endif<th class="text-center">Keputusan</th><th class="text-center">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($aktif as $r)
                        <tr class="border-b border-primary-200 transition-colors duration-300 hover:bg-blue-50">
                            <td class="px-3 py-2 text-left"><p class="font-bold">{{ $r->user->name }}</p><p class="text-xs">{{ $prodi($r->user) }}</p></td>
                            <td>{{ $r->user->nim }}</td><td>{{ $r->phone }}</td>
                            @if ($event->isPanitia())<td>{{ $r->divisi?->nama ?? '-' }}</td>@endif
                            <td class="text-center">
                                @if ($r->keputusan === 'terima')<span class="rounded border border-green-100 bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-150">{{ $tahap === 'seleksi_berkas' ? 'Berkas Sesuai' : 'Lolos' }}</span>
                                @elseif ($r->keputusan === 'tolak')<span class="rounded border border-red-100 bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-100">{{ $tahap === 'seleksi_berkas' ? 'Berkas Salah' : 'Tidak Lolos' }}</span>
                                @else<span class="rounded border border-primary-100 bg-blue-50 px-2 py-0.5 text-xs font-semibold text-primary-200">Belum Dinilai</span>@endif
                            </td>
                            <td class="py-2 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('manage.registrant', [$event, $r]) }}" class="inline-flex items-center gap-1 rounded bg-primary-200 px-2 py-1 text-xs font-semibold text-white transition hover:bg-primary-150"><x-ui-icon name="eye" class="h-3.5 w-3.5" /> Lihat Berkas</a>
                                    @if ($tahap === 'seleksi_wawancara')
                                        @foreach (['terima' => ['check', 'text-green-150 hover:bg-green-50'], 'tolak' => ['x', 'text-red-100 hover:bg-red-50']] as $k => [$ic, $cls])
                                            <form method="POST" action="{{ route('manage.keputusan', [$event, $r]) }}">@csrf<input type="hidden" name="keputusan" value="{{ $k }}"><button class="rounded p-1 transition {{ $cls }}" aria-label="{{ ucfirst($k) }}"><x-ui-icon :name="$ic" class="h-4 w-4" /></button></form>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-gray-100">Semua pendaftar pada tahap ini sudah diseleksi. Lanjutkan ke tahap berikutnya.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div data-domino class="flex justify-end gap-3">
            <a href="{{ route('manage.template', $event) }}" class="flex items-center gap-2 rounded-md bg-white px-4 py-2 text-sm font-semibold text-primary-200 shadow-[0_0_8px_rgba(64,103,159,0.4)] transition hover:bg-primary-200 hover:text-white"><x-ui-icon name="download" class="h-4 w-4" /> Download Template Sertifikat</a>
            <button type="button" data-modal-open="upload-sertif" class="flex items-center gap-2 rounded-md bg-primary-200 px-4 py-2 text-sm font-semibold text-white shadow-[0_0_8px_rgba(64,103,159,0.4)] transition hover:bg-primary-150"><x-ui-icon name="upload" class="h-4 w-4" /> Upload Sertifikat</button>
        </div>
        <div data-domino data-file-switch="#sertif-viewer" data-no-highlight class="overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]">
            <table class="w-full text-center text-sm text-black">
                <thead class="bg-primary-200 text-base font-bold leading-5 text-white"><tr><th class="px-3 py-3">Nama Lengkap Mahasiswa</th><th>NIM</th><th>Nomor Telp</th><th>{{ $event->isPanitia() ? 'Sie Bertugas' : 'Peran' }}</th><th class="text-center">Sertifikat</th></tr></thead>
                <tbody>
                    @forelse ($diterima as $r)
                        @php $s = $r->sertifikat(); @endphp
                        <tr class="border-b border-primary-200 transition-colors duration-300 hover:bg-blue-50">
                            <td class="px-3 py-2 text-left"><p class="font-bold">{{ $r->user->name }}</p><p class="text-xs">{{ $prodi($r->user) }}</p></td>
                            <td>{{ $r->user->nim }}</td><td>{{ $r->phone }}</td>
                            <td>{{ $event->isPanitia() ? $sie($r) : 'Peserta' }}</td>
                            <td class="text-center">
                                @if ($s)
                                    <button type="button" data-modal-open="lihat-sertif" data-file-url="{{ Files::url($s->file_sertif) }}" data-file-name="Sertifikat {{ $r->user->name }}" class="inline-flex items-center gap-1 rounded bg-primary-200 px-3 py-1 text-xs font-semibold text-white transition hover:bg-primary-150"><x-ui-icon name="file" class="h-3.5 w-3.5" /> Lihat Sertifikat</button>
                                @else
                                    <span class="text-xs text-gray-100">Belum diunggah</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-sm text-gray-100">Tidak ada pendaftar yang diterima.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($event->sertifikat->isNotEmpty())
            <div data-domino class="card p-5">
                <h2 class="font-bold text-primary-200">Sertifikat Terunggah</h2>
                <ul class="mt-3 divide-y divide-primary-50 text-xs">
                    @foreach ($event->sertifikat as $s)
                        <li class="flex items-center justify-between py-2">
                            <span>{{ $s->registration_id ? 'Khusus: ' . ($registrations->firstWhere('id', $s->registration_id)?->user->name ?? '-') : 'Sertifikat umum (semua penerima)' }} · {{ $s->created_at->translatedFormat('d M Y H:i') }}</span>
                            <span class="flex gap-2">
                                <a href="{{ Files::url($s->file_sertif) }}" target="_blank" rel="noopener" class="rounded p-1 text-primary-200 transition hover:bg-blue-50" aria-label="Buka"><x-ui-icon name="eye" class="h-4 w-4" /></a>
                                <form method="POST" action="{{ route('manage.sertifikat.destroy', [$event, $s->id]) }}" data-confirm="Hapus sertifikat ini?">@csrf @method('DELETE')<button class="rounded p-1 text-red-100 transition hover:bg-red-50" aria-label="Hapus"><x-ui-icon name="trash" class="h-4 w-4" /></button></form>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-modal id="lihat-sertif" width="max-w-3xl">
            <x-viewer id="sertif-viewer" class="shadow-none" />
        </x-modal>
        <x-modal id="upload-sertif" title="Upload Sertifikat" subtitle="PDF/JPG/PNG, maks. 5 MB" icon="upload" :open="$errors->has('file') || $errors->has('registration_id')">
            <form method="POST" action="{{ route('manage.sertifikat.store', $event) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="registration_id" class="mb-1 block text-sm font-medium">Penerima</label>
                    <select id="registration_id" name="registration_id" class="input cursor-pointer">
                        <option value="">Semua penerima (sertifikat umum)</option>
                        @foreach ($diterima as $r)<option value="{{ $r->id }}" @selected(old('registration_id') == $r->id)>{{ $r->user->name }} — {{ $r->user->nim }}</option>@endforeach
                    </select>
                    <x-field-error name="registration_id" />
                </div>
                <x-file-slot name="file" label="File Sertifikat" accept=".pdf,.jpg,.jpeg,.png" hint="PDF/JPG/PNG maks. 5 MB" required />
                <div class="flex justify-end gap-2">
                    <button type="button" data-modal-close class="rounded-md border border-primary-200 px-4 py-2 text-sm font-semibold text-primary-200 transition hover:bg-blue-50">Batal</button>
                    <button class="rounded-md bg-primary-200 px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-150">Unggah</button>
                </div>
            </form>
        </x-modal>
    @endif
    </div>
</x-admin-layout>
