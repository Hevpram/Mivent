{{-- Daftar Akun Mahasiswa (Figma: Registrasi 7:7745). prototype: Daftar Akun Mahasiswa -> Verifikasi Kode -> Login --}}
@php $field = fn ($n) => $errors->has($n) ? 'border-red-100' : ''; @endphp
<x-layout title="Daftar Akun" :navbar="false" :footer="false">
    <div class="relative min-h-screen overflow-hidden bg-primary-100 pt-10">
        <div style="background-image: url('{{ asset('mivent_assets/icons/auth-tile.svg') }}')" class="pointer-events-none absolute inset-x-0 top-0 h-[240px] bg-[length:56px_56px] opacity-40"></div>
        <div class="relative px-[51px]"><x-back-pill data-domino :href="route('login')" label="Kembali ke Login" /></div>
        <div class="relative mt-[31px] rounded-t-[80px] bg-primary-50 px-[218px] pb-16 pt-[120px]">
            <h1 data-domino class="text-gradient-mivent w-fit text-4xl font-bold">Daftar Akun Mahasiswa</h1>
            <p data-domino class="mt-2 max-w-[960px] text-lg text-black">Isi data diri kamu dengan lengkap untuk mulai mengeksplorasi kegiatan kampus, pendaftaran kepanitiaan, dan pemantauan akumulasi poin SKP terintegrasi.</p>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" data-once novalidate class="mt-8 flex flex-col gap-7">
                @csrf
                <section data-domino class="rounded-[20px] bg-primary-50 p-6 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                    <h2 class="flex items-center gap-3 text-xl font-bold text-black"><span class="flex size-[30px] items-center justify-center rounded-[6px] bg-primary-100/60 text-primary-200"><x-ui-icon name="id" class="h-5 w-5" /></span> Data Diri &amp; Akademik</h2>
                    <div class="mt-5 grid grid-cols-2 gap-x-[60px] gap-y-4 text-sm">
                        <div>
                            <label for="name" class="font-medium text-black">Nama Lengkap <span class="text-red-100">*</span></label>
                            <input id="name" name="name" value="{{ old('name') }}" required placeholder="Nama Lengkap Sesuai KRS/KTM" class="mt-2 h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('name') }}">
                            <p class="mt-1 text-xs text-black">Pastikan ejaan sesuai kartu identitas akademik.</p><x-field-error name="name" />
                        </div>
                        <div>
                            <label for="phone" class="font-medium text-black">Nomor Telp <span class="text-red-100">*</span></label>
                            <input id="phone" name="phone" value="{{ old('phone') }}" required inputmode="numeric" placeholder="Nomor Telp yang Aktif" class="mt-2 h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('phone') }}">
                            <p class="mt-1 text-xs text-black">Pastikan nomor yang dimasukkan merupakan nomor telepon aktif.</p><x-field-error name="phone" />
                        </div>
                        <div>
                            <label for="nim" class="font-medium text-black">NIM Mahasiswa <span class="text-red-100">*</span></label>
                            <input id="nim" name="nim" value="{{ old('nim') }}" required inputmode="numeric" placeholder="Contoh : 2508561000" class="mt-2 h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('nim') }}">
                            <x-field-error name="nim" />
                        </div>
                        <div>
                            <label for="email" class="font-medium text-black">Email Mahasiswa <span class="text-red-100">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@student.unud.ac.id" class="mt-2 h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('email') }}">
                            <p class="mt-1 text-xs text-black">Gunakan email resmi Universitas Udayana atau email aktif.</p><x-field-error name="email" />
                        </div>
                        <div>
                            <label for="jurusan_id" class="font-medium text-black">Program Studi FMIPA <span class="text-red-100">*</span></label>
                            <select style="background-image: url('{{ asset('mivent_assets/icons/field-chevron.svg') }}')" id="jurusan_id" name="jurusan_id" required class="mt-2 cursor-pointer appearance-none bg-[length:13px_6.5px] bg-[position:right_16px_center] bg-no-repeat h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('jurusan_id') }}">
                                <option value="">Pilih Program Studi</option>
                                @foreach ($jurusan as $j)<option value="{{ $j->id }}" @selected(old('jurusan_id') == $j->id)>{{ $j->nama }}</option>@endforeach
                            </select>
                            <x-field-error name="jurusan_id" />
                        </div>
                        <div>
                            <label for="password" class="font-medium text-black">Kata Sandi <span class="text-red-100">*</span></label>
                            <div class="mt-2"><x-password-input auth-sm placeholder="**********" required /></div>
                            <p class="mt-1 text-xs text-black">Kata Sandi harus minimal 8 karakter, terdiri dari huruf, angka, dan simbol.</p><x-field-error name="password" />
                        </div>
                        <div>
                            <label for="angkatan" class="font-medium text-black">Angkatan Masuk <span class="text-red-100">*</span></label>
                            <select style="background-image: url('{{ asset('mivent_assets/icons/field-chevron.svg') }}')" id="angkatan" name="angkatan" required class="mt-2 cursor-pointer appearance-none bg-[length:13px_6.5px] bg-[position:right_16px_center] bg-no-repeat h-10 w-full rounded-[6px] border border-gray-100 bg-white px-3 text-sm text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition placeholder:text-gray-100 focus:border-primary-200 focus:outline-none {{ $field('angkatan') }}">
                                <option value="">Pilih Tahun Angkatan</option>
                                @for ($y = now()->year + 1; $y >= 2015; $y--)<option value="{{ $y }}" @selected(old('angkatan') == $y)>{{ $y }}</option>@endfor
                            </select>
                            <p class="mt-1 text-xs text-black">Tahun registrasi semester awal kamu.</p><x-field-error name="angkatan" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="font-medium text-black">Konfirmasi Kata Sandi <span class="text-red-100">*</span></label>
                            <div class="mt-2"><x-password-input auth-sm name="password_confirmation" placeholder="**********" required /></div>
                        </div>
                    </div>
                </section>

                <section data-domino class="rounded-[20px] bg-primary-50 p-6 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                    <h2 class="flex items-center gap-3 text-xl font-bold text-black"><span class="flex size-[30px] items-center justify-center rounded-[6px] bg-primary-100/60 text-primary-200"><x-ui-icon name="award" class="h-5 w-5" /></span> Arsip Sertifikat (Opsional)</h2>
                    <p class="mt-4 flex gap-3 rounded-[10px] bg-green-50 px-4 py-3 text-sm leading-6 text-green-150"><x-ui-icon name="info" class="mt-0.5 h-4 w-4 shrink-0" /><span><b>Hemat waktu kamu!</b> Berkas yang diunggah di bawah ini akan otomatis terisi saat kamu mendaftar kegiatan kampus yang mensyaratkannya, sehingga kamu tidak perlu mengunggah ulang secara berulang kali.</span></p>
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        @foreach (\App\Models\User::sertifikatFields() as $f => $label)
                            <x-file-slot :name="$f" :label="$label" />
                        @endforeach
                    </div>
                </section>

                <section data-domino class="rounded-[20px] bg-primary-50 px-6 py-5 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
                    <label class="flex cursor-pointer items-start gap-3 text-base leading-7 text-black">
                        <x-checkbox name="setuju" value="1" :checked="(bool) old('setuju')" size="size-[27px]" radius="rounded-[6.75px]" />
                        Saya menyatakan data yang saya masukkan adalah benar dan valid. Saya menyetujui Syarat &amp; Ketentuan serta Kebijakan Privasi Portal Mivent Universitas Udayana.
                    </label>
                    <x-field-error name="setuju" />
                </section>

                <button data-domino type="submit" class="flex h-12 w-full items-center justify-center gap-4 rounded-[6px] bg-primary-150 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                    <x-ui-icon name="user" class="h-5 w-5" /> Daftar Akun Mahasiswa
                </button>
            </form>
        </div>
    </div>
</x-layout>
