{{-- Kelola Akun Ormawa (Admin FMIPA) --}}
@php
    use App\Support\Files;
    $lbl = 'mb-1 block text-xs font-medium';
    $req = '<span class="text-red-100">*</span>';
@endphp
<x-admin-layout title="Kelola Akun Ormawa">
    {{-- Figma: Kelola Akun Ormawa - Admin FMIPA 7:17469. prototype: Tambah Akun Ormawa -> modal tambah (7:17840); Lihat Detail -> modal detail akun --}}
    <div class="mx-auto flex w-[995px] max-w-full flex-col gap-6">
        <div class="flex items-center justify-between gap-6">
            <div class="flex flex-col gap-1">
                <h1 data-domino class="text-gradient-mivent w-fit text-4xl font-bold">Kelola Akun Ormawa</h1>
                <p data-domino class="w-[680px] max-w-full text-lg text-black">Mengelola akun organisasi mahasiswa di lingkungan FMIPA serta memastikan informasi dan data organisasi tetap akurat dan terkini.</p>
            </div>
            <button data-domino type="button" data-modal-open="tambah-ormawa" class="group flex shrink-0 items-center gap-2 rounded-[10px] bg-primary-150 px-4 py-3 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                <img src="{{ asset('mivent_assets/icons/a-plus.svg') }}" alt="" class="size-4 transition-transform duration-300 group-hover:rotate-90"> Tambah Akun Ormawa
            </button>
        </div>

        <form data-domino method="GET" class="flex w-[400px] max-w-full gap-2">
            <div class="relative flex-1">
                <img src="{{ asset('mivent_assets/icons/k-search.svg') }}" alt="" class="pointer-events-none absolute left-3 top-1/2 z-10 size-[21px] -translate-y-1/2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama ormawa, ketua, atau email" aria-label="Cari ormawa" class="h-[34px] w-full rounded-[6px] bg-white pl-[49px] pr-3 text-sm font-light text-black drop-shadow-[0_0_5px_rgba(64,103,159,0.25)] transition-all duration-300 placeholder:text-gray-100 focus:outline-none focus:drop-shadow-[0_0_5px_rgba(64,103,159,0.6)]">
            </div>
            <button class="flex h-[34px] w-[100px] items-center justify-center gap-2 rounded-[5px] bg-primary-200 text-base font-semibold text-white transition-all duration-300 hover:bg-primary-150"><img src="{{ asset('mivent_assets/icons/k-search-white.svg') }}" alt="" class="size-[20.268px]"> Search</button>
        </form>

        <div data-domino class="flex flex-col gap-5 overflow-hidden rounded-[20px] bg-primary-50 pb-5 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]">
            <div class="flex items-center gap-6 bg-primary-200 px-3 py-3 text-center text-base font-bold leading-5 text-white">
                <p class="w-[250px] shrink-0">Nama Ormawa</p>
                <p class="w-[180px] shrink-0">Ketua Umum</p>
                <p class="w-[135px] shrink-0">Nomor Telp</p>
                <p class="w-[180px] shrink-0">Email</p>
                <p class="w-[120px] shrink-0">Aksi</p>
            </div>
            <div class="flex flex-col">
                @forelse ($accounts as $a)
                    <div class="flex flex-col gap-1 transition-colors duration-300 hover:bg-blue-50">
                        <div class="flex items-center gap-6 px-3 py-2 text-center text-sm leading-5 text-black">
                            <p class="w-[250px] shrink-0">{{ $a->organisasi?->nama ?? $a->name }}{{ $a->organisasi?->singkatan ? ' (' . $a->organisasi->singkatan . ')' : '' }}</p>
                            <p class="w-[180px] shrink-0">{{ $a->organisasi?->nama_ketua ?? '-' }}</p>
                            <p class="w-[135px] shrink-0">{{ $a->organisasi?->nomor_telpon ?? $a->phone ?? '-' }}</p>
                            <p class="w-[180px] shrink-0 break-all">{{ $a->email }}</p>
                            <div class="flex w-[120px] shrink-0 justify-center">
                                <button type="button" data-modal-open="akun-{{ $a->id }}" class="flex h-[22px] w-[120px] items-center justify-center gap-1 rounded-[3px] bg-primary-200 text-sm font-medium text-white transition-all duration-300 hover:bg-primary-150">
                                    <img src="{{ asset('mivent_assets/icons/f-eye-white.svg') }}" alt="" class="h-[9.999px] w-[15.28px]"> Lihat Detail
                                </button>
                            </div>
                        </div>
                        <div class="h-px w-full bg-primary-200"></div>
                    </div>
                @empty
                    <p class="py-10 text-center text-sm text-gray-100">Belum ada akun ormawa.</p>
                @endforelse
            </div>
            <div class="flex items-center justify-between px-6">
                <p class="text-sm font-semibold leading-5 text-black">Show {{ $accounts->firstItem() ?? 0 }} to {{ $accounts->lastItem() ?? 0 }} entries</p>
                {{ $accounts->onEachSide(1)->links('partials.pagination') }}
            </div>
        </div>
    </div>

    {{-- Modal tambah --}}
    <x-modal id="tambah-ormawa" title="Tambah Akun Ormawa Baru" subtitle="Daftarkan organisasi mahasiswa baru ke sistem portal FMIPA" icon="home" :open="$errors->tambah->any()">
        <form method="POST" action="{{ route('fmipa.ormawa.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <h3 class="text-sm font-bold text-primary-200">Data Pokok Organisasi</h3>
            @foreach (['nama' => ['Nama Lengkap Ormawa', true, 'text', 'Himpunan Mahasiswa ...'], 'nama_ketua' => ['Nama Ketua Umum', true, 'text', 'Nama ketua umum'], 'nomor_telpon' => ['Nomor Telp Organisasi', true, 'tel', '08xxxxxxxxxx'], 'email' => ['Email Organisasi', true, 'email', 'ormawa@unud.ac.id'], 'akun_sosmed' => ['Akun Instagram Organisasi', false, 'text', '@ormawa.unud']] as $f => [$n, $r, $t, $ph])
                <div>
                    <label for="t-{{ $f }}" class="{{ $lbl }}">{{ $n }} {!! $r ? $req : '' !!}</label>
                    <input id="t-{{ $f }}" type="{{ $t }}" name="{{ $f }}" value="{{ old($f) }}" placeholder="{{ $ph }}" @required($r) @class(['input text-xs', 'is-invalid' => $errors->tambah->has($f)]) @if ($f === 'nomor_telpon') inputmode="numeric" pattern="[0-9]{10,13}" maxlength="13" @endif>
                    <x-field-error :name="$f" bag="tambah" />
                </div>
            @endforeach
            <div><label for="t-password" class="{{ $lbl }}">Password {!! $req !!}</label><x-password-input id="t-password" bag="tambah" placeholder="Password akun" required /></div>
            <div><label for="t-password_confirmation" class="{{ $lbl }}">Konfirmasi Password {!! $req !!}</label><x-password-input name="password_confirmation" id="t-password_confirmation" bag="tambah" placeholder="Ulangi password" required /></div>
            <div class="rounded-lg bg-blue-50 p-3"><p class="text-xs font-semibold text-primary-200">Persyaratan</p><x-password-rules class="mt-2" for="#t-password" confirm="#t-password_confirmation" /></div>
            <h3 class="pt-2 text-sm font-bold text-primary-200">Surat Keputusan Ormawa</h3>
            <x-file-slot name="sk_ormawa" label="Surat Keputusan Ormawa" accept=".pdf" hint="Support File: .pdf (maks 5 MB)" bag="tambah" required />
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close class="rounded-md border border-primary-200 px-6 py-2 text-sm font-semibold text-primary-200 transition hover:bg-blue-50">Batal</button>
                <button class="flex items-center gap-2 rounded-md bg-primary-150 px-5 py-2 text-sm font-semibold text-white transition hover:bg-primary-200"><x-ui-icon name="plus" class="h-4 w-4" /> Daftarkan Ormawa</button>
            </div>
        </form>
    </x-modal>

    {{-- Modal detail per akun --}}
    @foreach ($accounts as $a)
        @php $o = $a->organisasi; $bag = 'akun' . $a->id; @endphp
        <x-modal :id="'akun-' . $a->id" title="Detail Akun Ormawa" :subtitle="$o?->nama" icon="user" :open="$errors->{$bag}->any()">
            <div class="grid grid-cols-2 gap-3">
                <x-readonly-field label="Nama Lengkap Ormawa" :value="$o?->nama" />
                <x-readonly-field label="Nama Ketua Umum" :value="$o?->nama_ketua" />
                <x-readonly-field label="Nomor Telp Organisasi" :value="$o?->nomor_telpon ?? $a->phone" />
                <x-readonly-field label="Email Organisasi" :value="$a->email" />
                <x-readonly-field label="Akun Instagram" :value="$o?->akun_sosmed ?? '-'" />
                <x-readonly-field label="Tahun Kepengurusan" :value="$o?->tahun_kepengurusan ?? '-'" />
            </div>
            @if ($o?->sk_ormawa)
                <a href="{{ Files::url($o->sk_ormawa) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-primary-200 underline transition hover:text-primary-150"><x-ui-icon name="file" class="h-4 w-4" /> Lihat Surat Keputusan</a>
            @endif
            <form method="POST" action="{{ route('fmipa.ormawa.update', $a) }}" class="mt-4 space-y-3 border-t border-primary-50 pt-4">
                @csrf @method('PUT')
                <h3 class="text-sm font-bold text-primary-200">Ganti Password</h3>
                <x-password-input :id="'pw-' . $a->id" :bag="$bag" placeholder="Password baru" required />
                <x-password-input name="password_confirmation" :id="'pwc-' . $a->id" :bag="$bag" placeholder="Konfirmasi password baru" required />
                <div class="rounded-lg bg-blue-50 p-3"><x-password-rules :for="'#pw-' . $a->id" :confirm="'#pwc-' . $a->id" /></div>
                <button class="w-full rounded-md border border-primary-200 py-2 text-sm font-semibold text-primary-200 transition hover:bg-primary-200 hover:text-white">Update Password</button>
            </form>
        </x-modal>
    @endforeach
</x-admin-layout>
