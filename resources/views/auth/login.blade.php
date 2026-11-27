{{-- Login (Figma: Login 7:5419). prototype: Kembali ke Beranda, Lupa Kata Sandi -> Lupa Password, Daftar sekarang -> Registrasi --}}
<x-auth-split title="Login">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-1">
            <h1 data-domino class="text-4xl font-bold leading-normal text-primary-200">Selamat Datang Kembali!</h1>
            <p data-domino class="text-lg text-black">Masuk dan temukan event yang cocok buat kamu. Daftar sekali klik, data otomatis, sertifikat tersimpan rapi.</p>
        </div>

        <form data-domino data-once method="POST" action="{{ route('login') }}" class="flex flex-col gap-6" novalidate>
            @csrf
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-2">
                    <label for="identifier" class="text-base font-medium text-black">NIM / ID Ormawa <span class="text-red-100">*</span></label>
                    <div class="relative">
                        <img src="{{ asset('mivent_assets/icons/auth-id.svg') }}" alt="" class="pointer-events-none absolute left-5 top-1/2 z-10 size-[21px] -translate-y-1/2">
                        <input id="identifier" name="identifier" value="{{ old('identifier') }}" required autofocus autocomplete="username" placeholder="Masukin NIM Mahasiswa atau ID Ormawa"
                            class="input-auth pl-[53px] @error('identifier') border-red-100 @enderror">
                    </div>
                    <x-field-error name="identifier" />
                </div>
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col gap-2">
                        <label for="password" class="text-base font-medium text-black">Kata Sandi <span class="text-red-100">*</span></label>
                        <x-password-input auth required autocomplete="current-password" />
                        <x-field-error name="password" />
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary-200 transition-colors hover:text-primary-150">Lupa Kata Sandi?</a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                <button type="submit" class="flex h-12 w-full items-center justify-center rounded-[10px] bg-primary-150 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">Log In</button>
                <p class="flex items-center justify-center gap-2 text-base leading-[25px] text-black">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-primary-200 transition-colors hover:text-primary-150">Daftar sekarang</a></p>
            </div>
        </form>
    </div>
</x-auth-split>
