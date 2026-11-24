{{-- Pengaturan Akun Mahasiswa (Figma: Pengaturan Akun 7:3148; Logout -> modal 26:6043) --}}
@php
    $kartu = 'flex w-full flex-col gap-4 rounded-[10px] bg-primary-50 py-4 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]';
    $input = 'h-[35px] w-full rounded-[5px] border-[0.5px] border-black bg-primary-50 pl-2 text-sm leading-5 text-black transition focus:border-primary-200 focus:outline-none';
@endphp
<x-layout title="Pengaturan Akun">
    <section class="relative isolate flex justify-center px-14 pb-[46px] pt-20">
        <div class="pointer-events-none absolute inset-x-0 top-[634px] -z-10">
            <div class="h-[100px] bg-gradient-to-b from-background to-white"></div>
            <div class="h-[494px] bg-white"></div>
            <div class="h-[100px] bg-gradient-to-b from-white to-background"></div>
        </div>
        <div class="flex w-[999px] flex-col items-center gap-[60px]">
            <div class="flex flex-col items-center gap-2 text-center">
                <h1 data-domino class="text-4xl font-bold text-black">Pengaturan Akun <span class="text-gradient-mivent">Mivent</span></h1>
                <p data-domino class="h-[60px] w-[868px] text-lg text-black">Kelola Detail Akun, Perbarui Informasi Profil, dan Atur Keamanan untuk Menjaga Akses Tetap Aman</p>
            </div>

            <div class="flex w-full items-start gap-5">
                <div class="flex min-w-0 flex-1 flex-col gap-5">
                    {{-- Foto Profil --}}
                    <form data-domino method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data" class="{{ $kartu }}">
                        @csrf @method('PUT')
                        <div class="flex flex-col gap-3">
                            <h2 class="px-7 text-xl font-bold leading-7 text-primary-200">Foto Profil</h2>
                            <div class="h-px w-full bg-gray-100/50"></div>
                        </div>
                        <div class="flex items-center gap-3 px-7">
                            <div class="relative h-[100.7px] w-[103px] shrink-0">
                                <img id="avatar-preview" src="{{ $user->avatarUrl() }}" alt="Foto profil" class="size-[92px] rounded-[20px] border-[3px] border-primary-200 object-cover">
                                <span class="absolute left-[68.5px] top-[66.2px] flex size-[34.5px] items-center justify-center rounded-[6px] bg-primary-200"><img src="{{ asset('mivent_assets/icons/camera-badge.svg') }}" alt="" class="h-[25.362px] w-[25.134px]"></span>
                            </div>
                            <div class="flex flex-1 flex-col items-start gap-1.5">
                                <p class="text-lg font-bold leading-7 text-black">Upload Foto Baru</p>
                                <p class="text-sm leading-5 text-black">Unggah foto dengan format JPG, JPEG, atau PNG dengan ukuran maksimum 500 kB</p>
                                <label class="flex cursor-pointer items-center justify-center rounded-[10px] border border-primary-200 bg-primary-50 px-3 py-1 text-sm font-bold leading-5 text-primary-200 transition-all duration-300 hover:bg-primary-200 hover:text-white">
                                    Upload Foto<input type="file" name="avatar" accept=".jpg,.jpeg,.png" data-image-preview="#avatar-preview" data-max-kb="500" data-autosubmit class="sr-only">
                                </label>
                                <x-field-error name="avatar" />
                            </div>
                        </div>
                    </form>

                    {{-- Informasi Akun --}}
                    <form data-domino method="POST" action="{{ route('account.update') }}" data-once novalidate class="{{ $kartu }}">
                        @csrf @method('PUT')
                        <div class="flex flex-col gap-3">
                            <h2 class="px-7 text-xl font-bold leading-7 text-primary-200">Informasi Akun</h2>
                            <div class="h-px w-full bg-gray-100/50"></div>
                        </div>
                        <div class="flex flex-col gap-3 px-7">
                            <div class="flex flex-col gap-1">
                                <label for="name" class="text-sm font-medium leading-5 text-black">Nama Lengkap</label>
                                <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="{{ $input }} @error('name') border-red-100 @enderror"><x-field-error name="name" />
                            </div>
                            <x-readonly-field label="NIM Mahasiswa" :value="$user->nim" />
                            <x-readonly-field label="Email Mahasiswa" :value="$user->email" />
                            <div class="flex flex-col gap-1">
                                <label for="phone" class="text-sm font-medium leading-5 text-black">Nomor Handphone</label>
                                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required inputmode="numeric" class="{{ $input }} @error('phone') border-red-100 @enderror"><x-field-error name="phone" />
                            </div>
                            <x-readonly-field label="Program Studi FMIPA" :value="$user->jurusan?->nama" chevron />
                            <x-readonly-field label="Angkatan FMIPA" :value="$user->angkatan" chevron />
                            {{-- tombol simpan diperlukan backend (nama & nomor HP dapat diubah) --}}
                            <button type="submit" class="flex w-full items-center justify-center rounded-[5px] border border-primary-200 bg-primary-50 px-3 py-1 text-sm font-bold leading-5 text-primary-200 transition-all duration-300 hover:bg-primary-200 hover:text-white">Simpan Perubahan</button>
                        </div>
                    </form>

                    {{-- prototype: Logout Akun Mivent -> modal Yakin Ingin Logout? --}}
                    <button data-domino type="button" data-modal-open="logout" class="flex w-full items-center justify-center rounded-[10px] bg-primary-150 px-8 py-2 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-200">Logout Akun Mivent</button>
                </div>

                <div class="flex w-[489px] shrink-0 flex-col gap-5">
                    @include('partials.password-card')

                    {{-- Informasi Sertifikat --}}
                    <div data-domino class="flex w-full flex-col items-center gap-2.5 rounded-[10px] bg-primary-50 py-4 drop-shadow-[0_0_4px_rgba(64,103,159,0.5)]">
                        <div class="flex w-full flex-col gap-3">
                            <h2 class="px-7 text-xl font-bold leading-7 text-primary-200">Informasi Sertifikat</h2>
                            <div class="h-px w-full bg-gray-100/50"></div>
                        </div>
                        <div class="flex flex-col gap-4">
                            @foreach (\App\Models\User::sertifikatFields() as $f => $label)
                                @include('partials.berkas-row', ['field' => $f, 'label' => $label, 'path' => $user->{$f}, 'accept' => '.pdf,.jpg,.jpeg,.png'])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-logout-modal />
</x-layout>
