{{-- Landing page / Beranda (Figma: Landing Page 7:1321 & Landing Page - Sebelum Login 10:24670) --}}
<x-layout title="Beranda">
    {{-- ===== Hero ===== --}}
    <section class="flex justify-center px-[98px] pb-20 pt-20">
        <div class="flex w-[1244px] items-center gap-20">
            <div class="flex w-[665px] shrink-0 flex-col items-start gap-5">
                <h1 data-domino class="text-gradient-mivent w-[641px] text-5xl font-bold leading-normal">Semua Event dalam Satu Tempat</h1>
                <p data-domino class="text-lg text-black">Temukan seminar sains, kompetisi riset, olimpiade matematika, workshop laboratorium, dan kepanitiaan ormawa dengan perolehan poin SKP maksimal.</p>

                {{-- Pencarian cepat -> halaman Jelajahi Event (prototype: Cari Event -> Event) --}}
                <form data-domino action="{{ route('events.index') }}" method="GET" class="flex w-full justify-center rounded-[10px] bg-white py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.75)]">
                    <div class="flex items-center gap-7">
                        <label class="flex items-center gap-4">
                            <img src="{{ asset('mivent_assets/icons/search.svg') }}" alt="" class="size-[29px]">
                            <input type="search" name="q" placeholder="Cari kepanitiaan, seminar, dll" aria-label="Cari event"
                                class="w-[200px] bg-transparent text-sm font-light text-black placeholder:text-gray-100 focus:outline-none">
                            <span class="h-[18.5px] w-px bg-black"></span>
                        </label>
                        <label class="relative flex items-center gap-6">
                            <select name="kategori[]" aria-label="Kategori" class="cursor-pointer appearance-none bg-transparent pr-[44px] text-sm font-light text-black transition hover:text-primary-200 focus:outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach (\App\Models\Kategori::orderBy('id')->get() as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                            <img src="{{ asset('mivent_assets/icons/chevron-down.svg') }}" alt="" class="pointer-events-none absolute right-0 h-[10px] w-[20px]">
                        </label>
                        <span class="h-[18.5px] w-px bg-black"></span>
                        <button type="submit" class="rounded-[5px] bg-primary-150 px-4 py-1 text-base font-bold text-white transition-all duration-300 hover:bg-primary-200">Cari Event</button>
                    </div>
                </form>

                <div class="flex gap-5">
                    @foreach ([['chip-1', 'Berbagai Event', 'w-[19px]'], ['chip-2', 'Aman & terpercaya', 'w-[19px]'], ['chip-3', 'SKP Maksimal', 'w-[18px]']] as [$ikon, $chip, $lebar])
                        <span data-domino class="flex items-center gap-2.5 rounded-[15px] bg-white px-[18px] py-[11px] text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:-translate-y-0.5">
                            <img src="{{ asset('mivent_assets/icons/'.$ikon.'.svg') }}" alt="" class="h-[14px] {{ $lebar }}"> {{ $chip }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div data-domino class="relative h-[379.5px] w-[509px] shrink-0">
                <img src="{{ asset('mivent_assets/hero.png') }}" alt="Ilustrasi kegiatan mahasiswa FMIPA" class="size-full rounded-[20px] object-cover shadow-[0_0_15px_rgba(64,103,159,0.75)]">
                <img src="{{ asset('mivent_assets/icons/hero-logo.svg') }}" alt="" class="absolute left-[221px] top-[286px] h-[33.5px] w-[66.5px]">
            </div>
        </div>
    </section>

    {{-- ===== Apa itu Mivent ===== --}}
    <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
    <section class="flex min-h-[494px] justify-center bg-white px-[99px] pb-[69px] pt-[63px]">
        <div class="flex w-[1245px] items-start justify-between">
            <div class="flex w-[666px] flex-col items-start gap-2 text-black">
                <h2 data-domino class="text-4xl font-bold">Apa itu <span class="text-gradient-mivent">Mivent?</span></h2>
                <div data-domino class="flex flex-col gap-3 text-base">
                    <p>Mivent adalah portal digital resmi yang memadukan seluruh informasi kegiatan akademik dan non-akademik di lingkungan Fakultas Matematika dan Ilmu Pengetahuan Alam. Dibangun untuk menyederhanakan akses mahasiswa terhadap seminar riset, olimpiade sains, workshop laboratorium, dan rekrutmen kepanitiaan ormawa.</p>
                    <p>Melalui sistem ini, proses registrasi menjadi instan tanpa perlu pengisian data berulang. Setiap kegiatan yang kamu ikuti terhubung langsung dengan sistem verifikasi Dekanat, memastikan perolehan Poin Satuan Kredit Partisipasi (SKP) tervalidasi secara otomatis menuju transkrip kelulusan.</p>
                    <p>Bagi lembaga mahasiswa dan ormawa, portal ini memudahkan publikasi kegiatan terpadu, pengelolaan absensi berbasis kode QR, dan penerbitan e-sertifikat digital terstandarisasi.</p>
                </div>
            </div>
            <div class="flex w-[499px] shrink-0 flex-col gap-3 rounded-[20px] bg-primary-50 p-4 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
                @foreach ([['apa-1', 'size-[30px]', 'Temukan Event', 'Jelajahi kegiatan menarik sesuai minatmu'], ['apa-2', 'size-8', 'Daftar Kegiatan', 'Daftar kegiatan pilihan dengan mudah dan cepat.'], ['apa-3', 'size-8', 'Pantau Sertifikat', 'Pantau SKP dan sertifikat kegiatanmu dalam satu portal.']] as [$ikon, $ukuran, $judul, $teks])
                    <div data-domino class="flex items-center rounded-[5px] bg-white px-4 py-3 drop-shadow-[0_0_5px_rgba(64,103,159,0.25)] transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(64,103,159,0.5)]">
                        <div class="flex items-center gap-4">
                            <span class="flex size-16 shrink-0 items-center justify-center rounded-[10px] bg-primary-100 drop-shadow-[0_0_5px_rgba(64,103,159,0.25)]">
                                <img src="{{ asset('mivent_assets/icons/'.$ikon.'.svg') }}" alt="" class="{{ $ukuran }}">
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-xl font-bold leading-snug text-primary-200">{{ $judul }}</p>
                                <p class="mt-0.5 text-sm leading-normal text-black">{{ $teks }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>

    {{-- ===== Kenapa Harus Mivent ===== --}}
    <section class="flex justify-center px-20 py-[75px]">
        <div class="flex w-[1280px] flex-col items-center gap-8">
            <div class="flex w-[746px] flex-col items-center text-center text-black">
                <h2 data-domino class="text-4xl font-bold">Kenapa Harus <span class="text-gradient-mivent">Mivent?</span></h2>
                <p data-domino class="text-base">Dirancang khusus untuk memfasilitasi kebutuhan sivitas akademika sains dalam berkegiatan, berprestasi, dan mengklaim hak kredit kemahasiswaan.</p>
            </div>
            <div class="grid w-full grid-cols-2 items-start gap-6">
                @foreach ([
                    ['why-1', 'size-[48px]', 'Akses Informasi Terpusat', 'Semua event yang relevan bagi mahasiswa FMIPA dari organisasi internal FMIPA tersedia dalam satu platform, sehingga mahasiswa tidak perlu lagi mencari info tersebar di Instagram, grup WhatsApp, atau poster fisik yang mudah terlewat.'],
                    ['why-2', 'size-[45px]', 'Proses Rekrutmen Panitia Lebih Terarah', 'Timeline oprec, syarat pendaftaran, dan kebutuhan divisi ditampilkan langsung di halaman detail event, sehingga organisasi tidak perlu menjawab pertanyaan yang sama berulang kali lewat DM.'],
                    ['why-3', 'size-[45px]', 'Riwayat Keikutsertaan Tersimpan Rapi', 'Mahasiswa dapat melacak status event yang diikuti (sedang berjalan/selesai) tanpa harus mengingat-ingat sendiri di mana mereka sudah mendaftar.'],
                    ['why-4', 'size-[45px]', 'Administrasi yang Lebih Tertib', 'Dokumen pendukung seperti SIK, TOR, dan SK tersimpan sebagai bagian dari data event, sehingga proses verifikasi dan pengarsipan menjadi lebih rapi.'],
                ] as [$ikon, $ukuran, $judul, $teks])
                    <div data-domino class="flex h-[242px] flex-col gap-4 rounded-[20px] bg-primary-50 px-5 py-[26px] drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:drop-shadow-[0_0_10px_rgba(64,103,159,0.6)]">
                        <div class="flex items-center gap-4">
                            <span class="flex size-16 shrink-0 items-center justify-center rounded-[10px] bg-white drop-shadow-[0_0_5px_rgba(64,103,159,0.25)]">
                                <img src="{{ asset('mivent_assets/icons/'.$ikon.'.svg') }}" alt="" class="{{ $ukuran }}">
                            </span>
                            <p class="text-xl font-bold text-primary-200">{{ $judul }}</p>
                        </div>
                        <p class="mt-0.5 text-sm leading-normal text-black">{{ $teks }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Event Terdekat ===== --}}
    <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
    <section class="flex justify-center bg-white px-20 py-[14px]">
        <div class="flex w-[1280px] flex-col items-center gap-8">
            <div class="flex w-[746px] flex-col items-center text-center">
                <h2 data-domino class="text-4xl font-bold text-black"><span class="text-gradient-mivent">Event</span> Terdekat</h2>
                <p data-domino class="text-base text-black">Temukan berbagai event menarik yang akan berlangsung. Pilih kegiatan sesuai minatmu, daftarkan diri, dan jangan lewatkan kesempatan mendapatkan pengalaman baru.</p>
            </div>
            <div class="flex w-full items-center justify-center gap-8">
                @forelse ($events as $event)
                    <x-event-item :event="$event" />
                @empty
                    <p class="rounded-[20px] bg-primary-50 px-6 py-10 text-center text-sm text-gray-100 shadow-[0_0_8px_rgba(64,103,159,0.25)]">Belum ada event yang dipublikasikan.</p>
                @endforelse
            </div>
            {{-- prototype: Lihat Semua Event -> Event --}}
            <a data-domino href="{{ route('events.index') }}" class="group flex h-[54px] items-center justify-center gap-2 rounded-[10px] bg-primary-150 px-[35px] py-[14px] text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                Lihat Semua Event
                <img src="{{ asset('mivent_assets/icons/arrow-right.svg') }}" alt="" class="h-[23px] w-[14px] rotate-90 transition-transform duration-300 group-hover:translate-x-1">
            </a>
        </div>
    </section>
    <div class="mb-20 h-[100px] bg-gradient-to-b from-white to-background"></div>
</x-layout>
