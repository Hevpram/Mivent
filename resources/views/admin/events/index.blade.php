{{-- Kelola Event: daftar event milik pengelola (Figma: Kelola Event - Admin Ormawa 7:13044).
     prototype: Ajukan Event Baru -> Ajukan Event; Lihat -> detail event; Kelola -> Kelola Event detail; Perbaiki Event -> form revisi --}}
<x-admin-layout title="Kelola Event">
    <div class="mx-auto flex w-[995px] max-w-full flex-col gap-6">
        <div class="flex items-center justify-between">
            <div class="flex flex-col gap-1">
                <h1 data-domino class="text-gradient-mivent w-fit text-4xl font-bold">Event Saya</h1>
                <p data-domino class="w-[636px] text-lg text-black">Kelola jadwal, publikasi, pendaftar, dan sertifikat kepanitiaan serta peserta</p>
            </div>
            <a data-domino href="{{ route('manage.create') }}" class="group flex shrink-0 items-center gap-2 rounded-[10px] bg-primary-150 px-4 py-3 text-base font-bold text-white drop-shadow-[0_0_4px_rgba(64,103,159,0.75)] transition-all duration-300 hover:bg-primary-200">
                <img src="{{ asset('mivent_assets/icons/a-plus.svg') }}" alt="" class="size-4 transition-transform duration-300 group-hover:rotate-90"> {{ auth()->user()->isFmipa() ? 'Buat Event Baru' : 'Ajukan Event Baru' }}
            </a>
        </div>

        <form data-domino method="GET" class="flex items-end justify-between [&>div>p]:leading-5 [&>div>label]:leading-5 rounded-[20px] bg-primary-50 px-6 py-4 drop-shadow-[0_0_7.5px_rgba(64,103,159,0.75)]">
            <div class="flex w-[197px] flex-col gap-1">
                <p class="text-sm text-black">Rentang Tanggal</p>
                <x-date-range-calendar variant="box" nameStart="dari" nameEnd="sampai" :start="request('dari')" :end="request('sampai')" />
            </div>
            <div class="flex w-[211px] flex-col gap-1">
                <label for="status" class="text-sm text-black">Status</label>
                <select style="background-image: url('{{ asset('mivent_assets/icons/k-chevron.svg') }}')" id="status" name="status" class="cursor-pointer appearance-none rounded-[6px] border border-gray-100 bg-white bg-[length:13px_6.5px] bg-[position:right_8px_center] bg-no-repeat p-2 pr-7 text-xs text-black drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition-all duration-300 hover:border-primary-200 focus:border-primary-200 focus:outline-none">
                    <option value="">Total Keseluruhan Kegiatan</option>
                    @foreach (['menunggu' => 'Menunggu Verifikasi', 'revisi' => 'Verifikasi ditolak', 'pendaftaran' => 'Sedang Berlangsung', 'seleksi' => 'Proses Seleksi', 'selesai' => 'Acara Selesai'] as $v => $n)<option value="{{ $v }}" @selected(request('status') === $v)>{{ $n }}</option>@endforeach
                </select>
            </div>
            <div class="flex w-[289px] flex-col gap-1">
                <label for="q" class="text-sm text-black">Filter Event</label>
                <div class="relative">
                    <img src="{{ asset('mivent_assets/icons/k-search.svg') }}" alt="" class="pointer-events-none absolute left-3 top-1/2 z-10 size-[21px] -translate-y-1/2">
                    <input id="q" type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama event" class="h-[34px] w-full rounded-[6px] bg-white pl-[49px] pr-3 text-sm font-light text-black drop-shadow-[0_0_5px_rgba(64,103,159,0.25)] transition-all duration-300 placeholder:text-gray-100 focus:outline-none focus:drop-shadow-[0_0_5px_rgba(64,103,159,0.6)]">
                </div>
            </div>
            <button type="submit" class="flex h-[34px] w-[100px] items-center justify-center gap-2 rounded-[5px] bg-primary-200 text-base font-semibold leading-6 text-white transition-all duration-300 hover:bg-primary-150">
                <img src="{{ asset('mivent_assets/icons/k-search-white.svg') }}" alt="" class="size-[20.268px]"> Search
            </button>
            <a href="{{ route('manage.index') }}" class="group flex h-[34px] w-[100px] items-center justify-center gap-2 rounded-[5px] border border-primary-200 bg-white text-base font-semibold leading-6 text-primary-200 transition-all duration-300 hover:bg-blue-50">
                <img src="{{ asset('mivent_assets/icons/k-reset.svg') }}" alt="" class="size-[18.539px] transition-transform duration-500 group-hover:-rotate-180"> Reset
            </a>
        </form>

        <div data-domino class="flex flex-col overflow-hidden rounded-[20px] bg-primary-50 shadow-[0_0_15px_0_rgba(64,103,159,0.75)]">
            @include('partials.manage-table', ['events' => $events, 'flat' => true])
            <div class="flex items-center justify-between px-6 pb-5">
                <p class="text-sm font-semibold leading-5 text-black">Show {{ $events->firstItem() ?? 0 }} to {{ $events->lastItem() ?? 0 }} entries</p>
                {{ $events->onEachSide(1)->links('partials.pagination') }}
            </div>
        </div>
    </div>
</x-admin-layout>
