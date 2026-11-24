{{-- FAQ (Figma: FaQ 7:3372 / FaQ - Sebelum Login 10:26721) --}}
@php
    $faq = [
        ['Sharing', 'Gimana cara tahu event ini masuk kategori SKP apa?', 'Setiap event di FMIPA Event punya label kategori SKP. Kamu tinggal lihat badge-nya di card event atau halaman detail. Jadi gak perlu nebak-nebak lagi saat input di IMMISU Unud.'],
        ['Sharing', 'Apakah sertifikat saya otomatis masuk ke FMIPA Event?', 'Ya. Setelah event selesai dan panitia mengunggah e-sertifikat, sertifikatmu muncul di menu Event Diikuti dan bisa diunduh kapan saja.'],
        ['Sharing', 'Apakah sertifikat di FMIPA Event bisa langsung dipakai untuk SKP?', 'Sertifikat dari event yang sudah diverifikasi Admin FMIPA dapat kamu unggah ke IMMISU Unud sesuai kategori SKP yang tertera pada event.'],
        ['Sharing', 'Bagaimana kalau event saya bentrok dengan event lain?', 'Cek timeline di halaman detail setiap event sebelum mendaftar. Jika bentrok, hubungi narahubung event melalui tombol WhatsApp di halaman status pendaftaran.'],
        ['Sharing', 'Apa bedanya FMIPA Event dengan IMMISU Unud?', 'FMIPA Event adalah portal informasi dan pendaftaran event FMIPA, sedangkan IMMISU Unud adalah sistem resmi universitas untuk pencatatan poin SKP.'],
        ['Kendala', 'Apa yang harus dilakukan kalau website error?', 'Muat ulang halaman terlebih dahulu. Jika masih bermasalah, hubungi admin melalui kontak di bagian bawah halaman dengan menyertakan tangkapan layar error.'],
    ];
@endphp
<x-layout title="FAQ">
    <section class="relative isolate flex justify-center px-40 pb-[46px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex w-[1120px] flex-col items-center gap-[60px]">
            <div class="flex flex-col items-center gap-2 text-center">
                <h1 data-domino class="text-4xl font-bold text-black">Pertanyaan Umum <span class="text-gradient-mivent">Mivent</span></h1>
                <p data-domino class="h-[60px] w-[868px] text-lg text-black">Temukan jawaban dari pertanyaan yang paling sering ditanyakan seputar layanan FMIPA Event.</p>
            </div>

            <div class="flex w-full flex-col items-start gap-[30px]">
                @foreach ($faq as $i => [$tag, $tanya, $jawab])
                    @php($buka = $i === 0)
                    <div data-domino data-accordion class="flex min-h-[146px] w-full flex-col items-start gap-4 rounded-[10px] border-l-8 border-primary-200 bg-primary-50 p-6 drop-shadow-[0_0_3.5px_rgba(64,103,159,0.5)] transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(64,103,159,0.6)]">
                        <button type="button" data-accordion-btn aria-expanded="{{ $buka ? 'true' : 'false' }}" class="group flex w-full items-center justify-between text-left">
                            <span class="flex flex-col items-start gap-7">
                                <span class="flex w-[139px] items-center justify-center rounded-[6px] px-2.5 text-lg font-medium leading-7 {{ $tag === 'Kendala' ? 'border border-red-100 bg-red-50 text-red-100' : 'bg-primary-100/50 text-primary-200' }}">{{ $tag }}</span>
                                <span class="text-2xl font-semibold leading-8 text-black">{{ $tanya }}</span>
                            </span>
                            <span class="flex size-[52px] shrink-0 items-center justify-center rounded-[6px] border border-primary-200 bg-primary-50 transition-all duration-300 group-hover:bg-primary-100">
                                <img src="{{ asset('mivent_assets/icons/faq-chevron.svg') }}" alt="" data-accordion-arrow class="h-[10px] w-5 transition-transform duration-300 {{ $buka ? 'rotate-180' : '' }}">
                            </span>
                        </button>
                        <div data-accordion-body class="{{ $buka ? '' : 'hidden' }} flex flex-col gap-4">
                            <img src="{{ asset('mivent_assets/icons/faq-line.svg') }}" alt="" class="h-px w-[139px]">
                            <p class="w-[1020px] text-lg text-black">{{ $jawab }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layout>
