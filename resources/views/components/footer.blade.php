{{-- Footer (Figma: Footer 7:9295) --}}
<footer class="relative h-[254px] w-full overflow-clip bg-primary-200 font-poppins text-white">
    <div class="absolute left-[60px] right-[60px] top-6 flex h-[144px] items-start justify-between">
        <div class="flex w-[460px] flex-col items-start gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-[16.702px] transition hover:opacity-80">
                <img src="{{ asset('mivent_assets/icons/footer-logo.svg') }}" alt="Logo Mivent" class="h-[38.387px] w-[43.24px]">
                <span class="text-[33.404px] font-semibold">Mivent</span>
            </a>
            <p class="text-base">Mivent adalah platform web untuk mencari, mendaftar, dan mengelola event kemahasiswaan di lingkungan FMIPA Universitas Udayana.</p>
        </div>

        <div class="flex flex-col items-start gap-3">
            <p class="text-2xl font-bold">Navigasi</p>
            <div class="flex flex-col text-base">
                <a href="{{ route('home') }}" class="navbar_textIn navbar_textIn_white w-fit">Beranda</a>
                <a href="{{ route('events.index') }}" class="navbar_textIn navbar_textIn_white w-fit">Event</a>
                <a href="{{ route('registrations.index') }}" class="navbar_textIn navbar_textIn_white w-fit">Event Diikuti</a>
                <a href="{{ route('faq') }}" class="navbar_textIn navbar_textIn_white w-fit">FAQ</a>
            </div>
        </div>

        <div class="flex flex-col items-start gap-3">
            <p class="text-2xl font-bold">Bantuan</p>
            <div class="flex flex-col text-base">
                <a href="{{ route('faq') }}" class="navbar_textIn navbar_textIn_white w-fit">Tentang Kami</a>
                <a href="{{ route('faq') }}" class="navbar_textIn navbar_textIn_white w-fit">Panduan Pengguna</a>
                <a href="{{ route('faq') }}" class="navbar_textIn navbar_textIn_white w-fit">Kebijakan Privasi</a>
                <a href="{{ route('faq') }}" class="navbar_textIn navbar_textIn_white w-fit">Syarat &amp; Ketentuan</a>
            </div>
        </div>

        <div class="flex flex-col items-start gap-3">
            <p class="text-2xl font-bold">Kontak</p>
            <a href="https://maps.google.com/?q=FMIPA+Universitas+Udayana" target="_blank" rel="noopener" class="flex items-center gap-3 transition hover:opacity-75">
                <img src="{{ asset('mivent_assets/icons/footer-lokasi.svg') }}" alt="" class="h-6 w-[16.761px]">
                <span class="w-[216px] text-base">Gedung FMIPA, Universitas Udayana</span>
            </a>
            <a href="tel:036192947" class="flex items-center gap-3 transition hover:opacity-75">
                <img src="{{ asset('mivent_assets/icons/footer-telepon.svg') }}" alt="" class="size-5">
                <span class="w-[216px] text-base">(0361) 92947</span>
            </a>
        </div>
    </div>
    <div class="absolute left-0 top-[190px] h-px w-full bg-white"></div>
    <p class="absolute left-[60px] top-[202px] whitespace-nowrap text-base text-background">Copyright &copy; {{ date('Y') }} Mivent, Kelompok 1</p>
</footer>
