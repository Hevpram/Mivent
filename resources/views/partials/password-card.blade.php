{{-- Kartu "Ganti Password" (Figma: Pengaturan Akun 7:3148; bag: password) --}}
<form data-domino method="POST" action="{{ route('account.password') }}" data-once novalidate class="flex w-full flex-col gap-4 rounded-[10px] bg-primary-50 py-4 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
    @csrf @method('PUT')
    <div class="flex flex-col gap-3">
        <h2 class="px-7 text-xl font-bold leading-7 text-primary-200">Ganti Password</h2>
        <div class="h-px w-full bg-gray-100/50"></div>
    </div>
    <div class="flex flex-col gap-7">
        <div class="flex flex-col gap-5">
            <div class="flex flex-col gap-1 px-7">
                <label for="current_password" class="text-sm font-medium leading-5 text-black">Password Sekarang</label>
                <x-password-input compact name="current_password" bag="password" placeholder="•••••••••••••••" required autocomplete="current-password" />
                <p class="text-xs font-light leading-4 text-black">Silakan masukkan password saat ini untuk melanjutkan proses perubahan password</p>
                <x-field-error name="current_password" bag="password" />
            </div>
            <div class="flex flex-col gap-3">
                <div class="flex flex-col gap-1 px-7">
                    <label for="password" class="text-sm font-medium leading-5 text-black">Password Baru</label>
                    <x-password-input compact bag="password" placeholder="•••••••••••••••" required />
                    <x-field-error name="password" bag="password" />
                </div>
                <div class="flex flex-col gap-1 px-7">
                    <label for="password_confirmation" class="text-sm font-medium leading-5 text-black">Konfirmasi Password</label>
                    <x-password-input compact name="password_confirmation" bag="password" placeholder="•••••••••••••••" required />
                </div>
                <div class="px-7">
                    <div class="flex flex-col gap-2 rounded-[10px] bg-primary-100 p-3 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
                        <p class="text-sm font-bold leading-5 text-primary-200">Persyaratan</p>
                        <x-password-rules />
                    </div>
                </div>
            </div>
        </div>
        <div class="px-7">
            <button type="submit" class="flex w-full items-center justify-center rounded-[5px] border border-primary-200 bg-primary-50 px-3 py-1 text-sm font-bold leading-5 text-primary-200 transition-all duration-300 hover:bg-primary-200 hover:text-white">Update Pasword</button>
        </div>
    </div>
</form>
