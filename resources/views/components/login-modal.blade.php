{{-- Modal "Yuk, Login Dulu!" (Figma: Frame 4240 di Detail Event - Sebelum Login - Harus Login 16:4979 & Event Di Ikuti - Sebelum Login 10:26795).
     batal = URL tujuan tombol Batal; kosong -> modal ditutup saja. --}}
@props(['id' => 'login-wajib', 'batal' => null])
<div data-modal="{{ $id }}" class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/40 p-4 backdrop-blur-[2px]" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-judul">
    <div data-modal-panel class="flex w-[435px] flex-col items-end justify-center gap-8 rounded-[20px] border border-gray-100 bg-primary-50 px-6 py-5 drop-shadow-[0_0_8.55px_rgba(64,103,159,0.75)]">
        <div class="flex w-[387px] flex-col items-center gap-3">
            <img src="{{ asset('mivent_assets/icons/login-lock.svg') }}" alt="" class="size-[45px] scale-[1.125]">
            <div class="flex w-full flex-col gap-[3px] text-center">
                <p id="{{ $id }}-judul" class="text-2xl font-bold text-primary-200">Yuk, Login Dulu!</p>
                <p class="text-sm text-black">Satu langkah lagi untuk mengakses fitur ini. Login ke akun MIVENT-mu dan temukan kemudahan dalam setiap kegiatan kampus!</p>
            </div>
        </div>
        <div class="flex h-10 w-full items-start justify-end gap-5">
            @if ($batal)
                <a href="{{ $batal }}" class="flex items-center justify-center rounded-[6px] border border-primary-150 px-8 py-2 text-base font-bold text-primary-150 shadow-[0_0_8px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150 hover:text-white">Batal</a>
            @else
                <button type="button" data-modal-close class="flex items-center justify-center rounded-[6px] border border-primary-150 px-8 py-2 text-base font-bold text-primary-150 shadow-[0_0_8px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150 hover:text-white">Batal</button>
            @endif
            <a href="{{ route('login') }}" class="flex items-center justify-center rounded-[6px] bg-primary-200 px-6 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150">Login</a>
        </div>
    </div>
</div>
