{{-- Pengaturan Akun Admin Ormawa / Admin FMIPA --}}
@php $org = $user->organisasi; @endphp
<x-admin-layout title="Pengaturan Akun">
    <h1 data-domino class="text-4xl font-bold text-black">Pengaturan Akun <span class="text-gradient-mivent">Mivent</span></h1>

    <div class="mt-8 grid grid-cols-2 items-start gap-[33px]">
        <div class="flex flex-col gap-5">
            @if ($user->isOrmawa())
                <form data-domino method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data" class="card overflow-hidden p-0">
                    @csrf @method('PUT')
                    <h2 class="border-b border-gray-50 px-5 py-4 text-xl font-bold text-primary-200">Logo Organisasi</h2>
                    <div class="flex items-center gap-5 p-5">
                        <div class="relative">
                            <img id="logo-preview" src="{{ $user->avatarUrl() }}" alt="Logo organisasi" class="h-24 w-24 rounded-xl border-2 border-primary-200 bg-white object-contain">
                            <span class="absolute -bottom-2 -right-2 rounded-md bg-primary-200 p-1 text-white"><x-ui-icon name="image" class="h-4 w-4" /></span>
                        </div>
                        <div>
                            <p class="font-bold">Upload Logo Baru</p>
                            <p class="mt-1 text-xs">Unggah logo dengan format JPG, JPEG, atau PNG dengan ukuran maksimum 500 kB</p>
                            <label class="mt-2 inline-block cursor-pointer rounded-md border-2 border-primary-200 px-3 py-1 text-xs font-semibold text-primary-200 transition hover:bg-primary-200 hover:text-white">
                                Upload Logo<input type="file" name="logo" accept=".jpg,.jpeg,.png" data-image-preview="#logo-preview" data-max-kb="500" data-autosubmit class="sr-only">
                            </label>
                            <x-field-error name="logo" />
                        </div>
                    </div>
                </form>
                <div data-domino class="card overflow-hidden p-0">
                    <h2 class="border-b border-gray-50 px-5 py-4 text-xl font-bold text-primary-200">Informasi Surat Keputusan Ormawa</h2>
                    <div class="p-5">@include('partials.berkas-row', ['field' => 'sk_ormawa', 'label' => 'Surat Keputusan Ormawa', 'path' => $org?->sk_ormawa, 'accept' => '.pdf'])</div>
                </div>
            @endif

            @include('partials.password-card')
        </div>
        <div class="flex flex-col gap-5">
            {{-- Data pokok --}}
            <form data-domino method="POST" action="{{ route('account.update') }}" data-once novalidate class="card overflow-hidden p-0">
                @csrf @method('PUT')
                <h2 class="border-b border-gray-50 px-5 py-4 text-xl font-bold text-primary-200">{{ $user->isOrmawa() ? 'Data Pokok Organisasi' : 'Data Admin FMIPA' }}</h2>
                <div class="flex flex-col gap-3 p-5 text-sm">
                    @if ($user->isOrmawa())
                        <x-readonly-field label="Nama Lengkap Organisasi" :value="$org?->nama" />
                        <div><label for="nama_ketua" class="font-medium">Nama Ketua Umum <span class="text-red-100">*</span></label>
                            <input id="nama_ketua" name="nama_ketua" value="{{ old('nama_ketua', $org?->nama_ketua) }}" required class="input mt-1.5 @error('nama_ketua') border-red-100 @enderror"><x-field-error name="nama_ketua" /></div>
                    @else
                        <div><label for="name" class="font-medium">Nama <span class="text-red-100">*</span></label>
                            <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="input mt-1.5 @error('name') border-red-100 @enderror"><x-field-error name="name" /></div>
                    @endif
                    <div><label for="phone" class="font-medium">Nomor Telp{{ $user->isOrmawa() ? ' Organisasi' : '' }} <span class="text-red-100">*</span></label>
                        <input id="phone" name="phone" value="{{ old('phone', $user->phone ?? $org?->nomor_telpon) }}" required inputmode="numeric" class="input mt-1.5 @error('phone') border-red-100 @enderror"><x-field-error name="phone" /></div>
                    <div><label for="email" class="font-medium">Email{{ $user->isOrmawa() ? ' Organisasi' : '' }} <span class="text-red-100">*</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="input mt-1.5 @error('email') border-red-100 @enderror"><x-field-error name="email" /></div>
                    @if ($user->isOrmawa())
                        <div><label for="akun_sosmed" class="font-medium">Akun Instagram Organisasi</label>
                            <input id="akun_sosmed" name="akun_sosmed" value="{{ old('akun_sosmed', $org?->akun_sosmed) }}" placeholder="@akun" class="input mt-1.5"><x-field-error name="akun_sosmed" /></div>
                    @endif
                    <button type="submit" class="mt-1 rounded-md bg-primary-150 py-2 font-semibold text-white transition-all duration-300 hover:bg-primary-200">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
