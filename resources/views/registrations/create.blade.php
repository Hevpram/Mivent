{{-- Pendaftaran Event: 3 langkah (Figma: Daftar Event 1 7:4949 / 2 7:4752 / 3 7:5041). Panitia: sie + berkas; Peserta: hanya berkas syarat. --}}
@php
    $panitia = $event->isPanitia();
    $sertif = $panitia ? $event->requiredSertifikat() : [];
    $syaratBerkas = $panitia ? collect() : $event->syarat->where('butuh_berkas', true);
    $stepAwal = 0;
    if ($errors->hasAny(['divisi_id', 'divisi2_id', 'alasan']) || collect($errors->keys())->contains(fn ($k) => str_starts_with($k, 'berkas.') || str_starts_with($k, 'syarat.'))) $stepAwal = 1;
    elseif ($errors->has('setuju')) $stepAwal = 2;
    if ($errors->has('phone')) $stepAwal = 0;

    // Daftar berkas (dipakai di langkah 2 & ringkasan langkah 3)
    $berkas = [];
    foreach ($sertif as $f => $label) $berkas[] = ['name' => "berkas[$f]", 'label' => $label, 'path' => $user->{$f}, 'required' => ! $user->{$f}];
    if ($panitia && $event->req_instagram) $berkas[] = ['name' => 'berkas[instagram]', 'label' => 'Bukti Follow Instagram', 'path' => null, 'required' => true];
    foreach ($syaratBerkas as $s) $berkas[] = ['name' => "syarat[{$s->id}]", 'label' => $s->deskripsi, 'path' => null, 'required' => true];
    $idBerkas = fn ($name) => 'bk-' . trim(preg_replace('/[^a-z0-9]+/i', '-', $name), '-');

    $kartu = 'flex w-full flex-col rounded-[20px] bg-primary-50 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]';
    $kotak = 'flex flex-col justify-center gap-2 rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]';
    $panah = fn ($arah) => '<span class="flex h-[14px] w-[23px] items-center justify-center"><img src="' . asset('mivent_assets/icons/nav-arrow-' . $arah . '.svg') . '" alt="" class="h-[23px] w-[14px] max-w-none ' . ($arah === 'left' ? '-rotate-90 group-hover:-translate-x-1' : 'rotate-90 group-hover:translate-x-1') . ' transition-transform duration-300"></span>';
@endphp
<x-layout title="Pendaftaran Event">
    <section class="relative isolate flex justify-center px-14 pb-[56px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex flex-col items-center gap-[60px]">
            <div class="flex flex-col items-center gap-2 text-center">
                <h1 data-domino class="text-4xl font-bold text-black">Pendaftaran <span class="text-gradient-mivent">Event</span></h1>
                <p data-domino class="h-[60px] w-[868px] text-lg text-black">Temukan jawaban dari pertanyaan yang paling sering ditanyakan seputar layanan FMIPA Event.</p>
            </div>

            <div class="flex w-[925px] flex-col items-start gap-10">
                <x-step-indicator :steps="[['Data Diri Mahasiswa', 'Sinkronisasi Langsung'], [$panitia ? 'Berkas & Posisi' : 'Berkas Persyaratan', 'Upload Sertifikat'], ['Konfirmasi & Validasi', 'Review & Finalisasi']]" />

                <form method="POST" action="{{ route('registrations.store', $event) }}" enctype="multipart/form-data" data-steps="{{ $stepAwal }}" novalidate class="w-full">
                    @csrf
                    {{-- ===== Langkah 1: Data Diri ===== --}}
                    <div data-step class="flex flex-col gap-10">
                        <div data-domino class="{{ $kartu }} gap-[18px] px-8 py-6">
                            <div class="flex w-full items-start gap-3 rounded-[10px] bg-blue-50 px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                                <img src="{{ asset('mivent_assets/icons/sync-cloud.svg') }}" alt="" class="mt-0.5 h-[14.667px] w-[20.167px]">
                                <div class="flex flex-1 flex-col gap-1">
                                    <p class="text-sm font-bold text-primary-200">Sinkronisasi Pangkalan Data Otomatis</p>
                                    <p class="text-xs text-black">Data akademik di bawah ini diambil langsung dari Mivent. Perubahan data induk hanya dapat dilakukan melalui Bagian <a href="{{ route('account.edit') }}" class="transition-colors hover:text-primary-200 hover:underline">Edit Akun</a>.</p>
                                </div>
                            </div>
                            <div class="grid w-full grid-cols-2 gap-5">
                                <x-readonly-field label="Nama Lengkap" :value="$user->name" />
                                <x-readonly-field label="NIM Mahasiswa" :value="$user->nim" />
                                <x-readonly-field label="Program Studi FMIPA" :value="$user->jurusan?->nama" />
                                <x-readonly-field label="Angkatan FMIPA" :value="$user->angkatan" />
                                <x-readonly-field label="Email Mahasiswa" :value="$user->email" />
                                <div class="flex flex-col gap-1">
                                    <label for="phone" class="text-sm font-medium leading-5 text-black">Nomor Telp <span class="text-red-100">*</span></label>
                                    <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required pattern="[0-9]{10,13}" inputmode="numeric" class="h-[35px] w-full rounded-[5px] border-[0.5px] border-gray-100 bg-white pl-2 text-sm leading-5 text-black transition focus:border-primary-200 focus:outline-none @error('phone') border-red-100 @enderror">
                                    <p class="text-xs font-light text-black">Jika anda merubahnya, pastikan nomor yang dimasukkan merupakan nomor telpon aktif</p>
                                    <x-field-error name="phone" />
                                </div>
                            </div>
                        </div>
                        <div data-domino class="{{ $kartu }} h-[72px] items-center justify-center px-8 py-4">
                            <div class="flex w-[858px] items-start justify-between">
                                {{-- prototype: Kembali ke Detail Event --}}
                                <a href="{{ route('events.show', $event) }}" class="btn-outline-nav group">{!! $panah('left') !!} Kembali ke Detail Event</a>
                                <button type="button" data-step-next class="btn-fill-nav group">Lanjut ke {{ $panitia ? 'Berkas & Posisi' : 'Berkas Persyaratan' }} {!! $panah('right') !!}</button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Langkah 2: Berkas & Posisi ===== --}}
                    <div data-step class="hidden flex-col gap-10 [&:not(.hidden)]:flex">
                        <div class="{{ $kartu }} gap-[18px] px-8 py-6">
                            <div class="flex w-full items-start gap-3 rounded-[10px] bg-green-50 px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                                <img src="{{ asset('mivent_assets/icons/check-small.svg') }}" alt="" class="mt-0.5 size-[15px]">
                                <div class="flex flex-1 flex-col gap-1">
                                    <p class="text-sm font-bold text-green-150">{{ $panitia ? 'Persyaratan Berkas Otomatis Terhubung' : 'Persyaratan Berkas Pendaftaran' }}</p>
                                    <p class="text-xs text-black">{{ $panitia ? 'Penyelenggara mensyaratkan sertifikat berikut. Sistem mendeteksi arsip sertifikat digital profil kamu yang siap dilampirkan otomatis tanpa perlu upload ulang.' : 'Unggah berkas untuk setiap persyaratan berikut (pdf/jpg/png, maks 2 MB).' }}</p>
                                </div>
                            </div>

                            @if ($berkas)
                                <div class="flex w-full flex-col gap-4">
                                    <p class="text-base font-bold text-primary-200">{{ $panitia ? 'Berkas Wajib Persyaratan Kepanitiaan' : 'Berkas Wajib Persyaratan' }}</p>
                                    <div class="grid w-full grid-cols-2 gap-5">
                                        @foreach ($berkas as $b)
                                            <x-berkas-card :name="$b['name']" :label="$b['label']" :path="$b['path']" :required="$b['required']" auto />
                                        @endforeach
                                    </div>
                                </div>
                            @elseif (! $panitia)
                                <p class="w-full rounded-[10px] bg-blue-50 px-4 py-6 text-center text-sm text-black">Event ini tidak membutuhkan unggahan berkas. Lanjutkan ke konfirmasi.</p>
                            @endif

                            @if ($panitia)
                                <div class="flex w-full flex-col gap-4">
                                    <p class="text-base font-bold text-primary-200">Posisi Kepanitiaan</p>
                                    <div class="flex w-full flex-col gap-5">
                                        <div data-sie-select data-max="2" class="flex flex-col gap-1">
                                            <p class="text-sm leading-5 text-black">Pilih divisi yang diikuti <span class="text-red-100">*</span></p>
                                            <input type="hidden" name="divisi_id" value="{{ old('divisi_id') }}" data-sie-1 data-step-require="Pilih minimal 1 sie terlebih dahulu.">
                                            <input type="hidden" name="divisi2_id" value="{{ old('divisi2_id') }}" data-sie-2>
                                            <div class="flex flex-col gap-2">
                                                <div data-sie-toggle role="button" tabindex="0" class="flex min-h-[37px] cursor-pointer items-center justify-between rounded-[6px] border border-gray-100 bg-white px-3 py-2 pr-[25px] drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]">
                                                    <div class="flex flex-1 flex-wrap items-center gap-3">
                                                        <div data-sie-chips class="flex flex-wrap items-center gap-3"></div>
                                                        <span data-sie-chips-empty class="text-xs text-gray-100">Pilih maksimal 2 divisi</span>
                                                    </div>
                                                    <img data-sie-chevron src="{{ asset('mivent_assets/icons/field-chevron.svg') }}" alt="" class="h-[6.5px] w-[13px] rotate-180 transition-transform duration-300">
                                                </div>
                                                <template data-sie-chip>
                                                    <span class="flex items-center gap-1 rounded-[5px] bg-primary-200 px-2 py-0.5 text-xs font-semibold leading-4 text-white"><span data-chip-label></span><button type="button" data-chip-remove aria-label="Hapus" class="flex size-3 items-center justify-center rounded-full transition hover:bg-primary-150"><img src="{{ asset('mivent_assets/icons/chip-x.svg') }}" alt="" class="size-[5px]"></button></span>
                                                </template>
                                                <div data-sie-list class="flex flex-col gap-2 rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)]">
                                                    @foreach ($event->divisi as $d)
                                                        <label class="group flex cursor-pointer items-center gap-2.5 border-b border-primary-200 pb-[5px] pr-[13px] text-xs font-medium leading-5 text-black transition-colors last:border-b-0 has-[:disabled]:cursor-not-allowed has-[:disabled]:text-gray-100">
                                                            <x-checkbox data-sie data-nama="{{ $d->nama }}" value="{{ $d->id }}" radius="rounded-[3px]" />
                                                            <span class="flex-1">{{ $d->nama }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <p class="text-sm leading-5 text-black">Pilih maksimal 2 divisi sesuai minat dan kemampuanmu.</p>
                                            <x-field-error name="divisi_id" /><x-field-error name="divisi2_id" />
                                        </div>
                                        <div class="flex flex-col gap-1">
                                            <label for="alasan" class="text-sm leading-5 text-black">Tautan Portofolio / Alasan Memilih Divisi <span class="text-red-100">*</span></label>
                                            <textarea id="alasan" name="alasan" rows="2" maxlength="1000" required placeholder="Ceritakan pengalaman atau tautan portofolio kamu" class="w-full rounded-[6px] border border-gray-100 bg-white p-3 text-xs leading-5 text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] placeholder:text-gray-100 focus:border-primary-200 focus:outline-none @error('alasan') border-red-100 @enderror">{{ old('alasan') }}</textarea>
                                            <x-field-error name="alasan" />
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="{{ $kartu }} items-center justify-center px-8 py-4">
                            <div class="flex h-10 w-[858px] items-start justify-between">
                                <a href="{{ route('events.show', $event) }}" class="btn-outline-nav group">{!! $panah('left') !!} Kembali ke Detail Event</a>
                                <button type="button" data-step-next class="btn-fill-nav group">Lanjut ke Konfirmasi &amp; Validasi {!! $panah('right') !!}</button>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Langkah 3: Konfirmasi & Validasi ===== --}}
                    <div data-step class="hidden flex-col gap-10 [&:not(.hidden)]:flex">
                        <div class="{{ $kartu }} gap-[18px] px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <h2 class="text-xl font-bold text-primary-200">Ringkasan Berkas &amp; Validasi Pendaftaran</h2>
                                <p class="text-base text-black">Harap teliti kembali seluruh data pendaftaran Anda sebelum dikirimkan ke penyelenggara</p>
                            </div>
                            <div class="flex w-full items-start gap-5">
                                <div class="{{ $kotak }} flex-1">
                                    <p class="flex items-center gap-2 text-sm font-bold capitalize text-primary-200"><img src="{{ asset('mivent_assets/icons/ic-user.svg') }}" alt="" class="size-[10.667px]"> Identitas Pendaftar</p>
                                    <div class="flex flex-col gap-3 text-xs leading-[15px] text-black">
                                        @foreach (['Nama Lengkap' => $user->name, 'NIM Mahasiswa' => $user->nim, 'Program Studi FMIPA' => $user->jurusan?->nama, 'Angkatan FMIPA' => $user->angkatan, 'Email Mahasiswa' => $user->email] as $k => $v)
                                            <p class="flex justify-between gap-3"><span class="shrink-0">{{ $k }} :</span><b class="text-right font-semibold">{{ $v }}</b></p>
                                        @endforeach
                                        <p class="flex justify-between gap-3"><span>Nomor Telp :</span><b data-mirror="#phone" class="font-semibold"></b></p>
                                    </div>
                                </div>
                                <div class="{{ $kotak }} flex-1">
                                    <p class="flex items-center gap-2 text-sm font-bold capitalize text-primary-200"><img src="{{ asset('mivent_assets/icons/ic-rekap.svg') }}" alt="" class="h-[13.333px] w-[12.667px]"> Rekapan Pilihan</p>
                                    <div class="flex flex-col gap-3 text-xs leading-[15px] text-black">
                                        <p class="flex justify-between gap-3"><span class="shrink-0">Peran Pendaftaran :</span><b class="font-semibold">{{ $panitia ? 'Panitia Pelaksana' : 'Peserta' }}</b></p>
                                        @if ($panitia)
                                            <p class="flex justify-between gap-3"><span class="shrink-0">Divisi Pilihan 1 :</span><b data-mirror-sie="1" class="text-right font-semibold">-</b></p>
                                            <p class="flex justify-between gap-3"><span class="shrink-0">Divisi Pilihan 2 :</span><b data-mirror-sie="2" class="text-right font-semibold">-</b></p>
                                        @endif
                                        <p class="flex justify-between gap-3"><span class="shrink-0">Penyelenggara :</span><b class="text-right font-semibold">{{ $event->organisasi?->nama }}</b></p>
                                        <p class="flex justify-between gap-3"><span class="shrink-0">Tanggal Pelaksanaan :</span><b class="text-right font-semibold">{{ $event->rentangTanggal() }}</b></p>
                                    </div>
                                </div>
                            </div>
                            @if ($berkas)
                                <div class="{{ $kotak }} w-full">
                                    <p class="flex items-center gap-2 text-sm font-bold capitalize text-primary-200"><img src="{{ asset('mivent_assets/icons/ic-berkas.svg') }}" alt="" class="h-[16.667px] w-[15px]"> Berkas Terlampir</p>
                                    <div class="grid w-full grid-cols-2 gap-2">
                                        @foreach ($berkas as $b)
                                            <div class="{{ $kotak }}">
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ asset('mivent_assets/icons/file-doc.svg') }}" alt="" class="size-8 shrink-0">
                                                    <div class="flex min-w-0 flex-col gap-0.5">
                                                        <p class="text-sm font-bold leading-[18px] text-primary-200">{{ $b['label'] }}</p>
                                                        <p data-mirror-file="#{{ $idBerkas($b['name']) }}" data-default="{{ $b['path'] ? basename($b['path']) : 'Belum ada berkas' }}" class="truncate text-xs leading-[15px] text-black">{{ $b['path'] ? basename($b['path']) : 'Belum ada berkas' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end">
                                                    @if ($b['path'])
                                                        <a href="{{ \App\Support\Files::url($b['path']) }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-[6px] border border-primary-200 bg-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-white transition-all duration-300 hover:bg-primary-150"><img src="{{ asset('mivent_assets/icons/eye-white.svg') }}" alt="" class="h-[10.469px] w-4"> Lihat Berkas</a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="{{ $kartu }} px-6 py-5">
                            <label class="flex cursor-pointer items-start gap-3 text-base leading-7 text-black">
                                <x-checkbox name="setuju" value="1" required size="mt-0.5 size-[27px]" radius="rounded-[6.75px]" />
                                <span>Saya menyatakan seluruh data dan berkas yang dilampirkan adalah sah, absah, dan benar sesuai arsip akademik. Saya bersedia mengikuti seluruh alur {{ $panitia ? 'seleksi dan komitmen kepanitiaan' : 'kegiatan' }} {{ $event->nama_kegiatan }} secara penuh sesuai pakta integritas Dekanat FMIPA UNUD.</span>
                            </label>
                            <x-field-error name="setuju" />
                        </div>
                        <div class="{{ $kartu }} items-center px-8 py-4">
                            <div class="flex w-[858px] flex-col gap-2.5">
                                {{-- prototype: Kirim Pendaftaran Sekarang -> Daftar Event 4 (modal Konfirmasi Registrasi) --}}
                                <button type="submit" class="btn-fill-nav group w-full">Kirim Pendaftaran Sekarang <img src="{{ asset('mivent_assets/icons/ic-send.svg') }}" alt="" class="h-[20.067px] w-5 transition-transform duration-300 group-hover:translate-x-1"></button>
                                {{-- prototype: Edit Data atau Formasi Berkas -> Daftar Event 1 --}}
                                <button type="button" data-step-goto="0" class="btn-outline-nav group w-full"><img src="{{ asset('mivent_assets/icons/ic-pencil.svg') }}" alt="" class="size-[17px]"> Edit Data atau Formasi Berkas</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layout>
