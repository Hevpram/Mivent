{{-- Ganti Password (Figma: Lupa Password - Password Baru 7:6958). prototype: Ganti Password Anda -> Login --}}
<x-auth-split title="Atur Ulang Kata Sandi" :back="route('login')" back-label="Kembali ke Login">
    <div class="flex flex-col gap-5">
        <div class="flex flex-col gap-1">
            <h1 data-domino class="text-4xl font-bold leading-normal text-primary-200">Ganti Password</h1>
            <p data-domino class="text-lg text-black">Perbarui kata sandi akunmu untuk menjaga keamanan dan akses tetap terlindungi.</p>
        </div>
        <form data-domino data-once method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5" novalidate>
            @csrf
            <div class="flex flex-col gap-1">
                <label for="password" class="text-base font-medium text-black">Password Baru <span class="text-red-100">*</span></label>
                <x-password-input auth required placeholder="•••••••••••••••" />
                <x-field-error name="password" />
            </div>
            <div class="flex flex-col gap-1">
                <label for="password_confirmation" class="text-base font-medium text-black">Konfirmasi Password Baru <span class="text-red-100">*</span></label>
                <x-password-input auth required name="password_confirmation" placeholder="•••••••••••••••" />
            </div>
            <div class="flex flex-col gap-2 rounded-[10px] bg-primary-100 p-3 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
                <p class="text-sm font-bold leading-5 text-primary-200">Persyaratan</p>
                <x-password-rules />
            </div>
            <button type="submit" class="flex h-12 w-full items-center justify-center rounded-[10px] bg-primary-150 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">Ganti Password Anda</button>
        </form>
    </div>
</x-auth-split>
