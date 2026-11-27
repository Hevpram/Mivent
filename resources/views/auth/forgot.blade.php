{{-- Lupa Password (Figma: Lupa Password 7:6198). prototype: -> Verifikasi Kode --}}
<x-auth-split title="Lupa Kata Sandi" :back="route('login')" back-label="Kembali ke Login">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-1">
            <h1 data-domino class="text-4xl font-bold leading-normal text-primary-200">Pulihkan Akses Akun Anda</h1>
            <p data-domino class="text-lg text-black">Masukkan email terdaftar untuk mendapatkan tautan pemulihan akses akun Anda.</p>
        </div>
        <form data-domino data-once method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5" novalidate>
            @csrf
            <div class="flex flex-col gap-2">
                <label for="akun" class="text-base font-medium text-black">Email/ Nomor WhatsApp <span class="text-red-100">*</span></label>
                <input id="akun" name="akun" value="{{ old('akun') }}" required autofocus placeholder="Masukkan email/ nomor whatsapp anda.." class="input-auth @error('akun') border-red-100 @enderror">
                <x-field-error name="akun" />
            </div>
            <button type="submit" class="flex h-12 w-full items-center justify-center rounded-[10px] bg-primary-150 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">Cari Email/ Nomor WhatsApps</button>
        </form>
    </div>
</x-auth-split>
