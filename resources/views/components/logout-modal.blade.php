{{-- Modal "Yakin Ingin Logout?" (Figma: Pengaturan Akun - Logout 26:6043; dipakai juga di sidebar admin 17:8261 / 17:7921).
     prototype: Batal -> tutup modal; Logout -> keluar lalu ke Landing Page (sebelum login). --}}
@props(['id' => 'logout'])
<div data-modal="{{ $id }}" class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/20 p-4 backdrop-blur-[2px]" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-judul">
    <form data-modal-panel method="POST" action="{{ route('logout') }}" class="flex w-[435px] flex-col items-end justify-center gap-8 rounded-[20px] border border-gray-100 bg-primary-50 px-6 py-5 drop-shadow-[0_0_8.55px_rgba(64,103,159,0.75)]">
        @csrf
        <div class="flex w-[387px] flex-col items-center gap-3">
            <img src="{{ asset('mivent_assets/icons/logout-door.svg') }}" alt="" class="h-[46.716px] w-[55px]">
            <div class="flex w-full flex-col gap-[3px] text-center">
                <p id="{{ $id }}-judul" class="text-2xl font-bold text-primary-200">Yakin Ingin Logout?</p>
                <p class="text-sm text-black">Anda akan keluar dari akun MIVENT. Pastikan semua aktivitas telah selesai sebelum melanjutkan.</p>
            </div>
        </div>
        <div class="flex h-10 w-full items-start justify-end gap-5">
            <button type="button" data-modal-close class="flex items-center justify-center rounded-[6px] border border-primary-150 px-8 py-2 text-base font-bold text-primary-150 shadow-[0_0_8px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150 hover:text-white">Batal</button>
            <button type="submit" class="flex items-center justify-center rounded-[6px] bg-primary-200 px-6 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-150">Logout</button>
        </div>
    </form>
</div>
