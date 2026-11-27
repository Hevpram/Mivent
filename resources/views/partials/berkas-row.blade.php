{{-- Baris berkas tersimpan + "Lihat Berkas" / "Ganti Berkas" (Figma: Informasi Sertifikat di Pengaturan Akun; autosubmit). Variabel: $field, $label, $path, $accept --}}
<form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data" class="flex w-[433px] flex-col gap-2 rounded-[6px] border border-gray-100 bg-white px-3 py-2 drop-shadow-[0_0_4px_rgba(64,103,159,0.25)] transition-all duration-300 hover:drop-shadow-[0_0_8px_rgba(64,103,159,0.5)]">
    @csrf @method('PUT')
    <div class="flex items-center gap-3">
        <img src="{{ asset('mivent_assets/icons/file-doc.svg') }}" alt="" class="size-8">
        <div class="flex min-w-0 flex-col gap-0.5">
            <p class="text-sm font-bold leading-[18px] text-primary-200">{{ $label }}</p>
            <p class="truncate text-xs leading-[15px] text-black">{{ $path ? basename($path) : 'Belum ada berkas' }}</p>
        </div>
    </div>
    <div class="flex items-start justify-between">
        @if ($path)
            <a href="{{ \App\Support\Files::url($path) }}" target="_blank" rel="noopener" class="flex items-center gap-2 rounded-[6px] border border-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-primary-200 transition-all duration-300 hover:bg-blue-50"><img src="{{ asset('mivent_assets/icons/eye-outline.svg') }}" alt="" class="h-[10.469px] w-4"> Lihat Berkas</a>
        @else
            <span></span>
        @endif
        <label class="flex cursor-pointer items-center gap-2 rounded-[6px] border border-white bg-primary-200 px-2.5 py-1 text-xs font-medium leading-[16.5px] text-white transition-all duration-300 hover:bg-primary-150">
            <img src="{{ asset('mivent_assets/icons/swap-white.svg') }}" alt="" class="h-[10.667px] w-[13.333px]"> {{ $path ? 'Ganti Berkas' : 'Unggah Berkas' }}
            <input type="file" name="{{ $field }}" accept="{{ $accept }}" data-autosubmit-change class="sr-only">
        </label>
    </div>
    <x-field-error :name="$field" />
</form>
