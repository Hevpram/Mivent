{{-- Field terkunci (Figma: input abu "NIM Mahasiswa" + ikon gembok; chevron untuk field pilihan seperti Program Studi / Angkatan) --}}
@props(['label', 'value', 'chevron' => false])
<div class="flex flex-col gap-1">
    <p class="text-sm font-medium leading-5 text-black">{{ $label }}</p>
    <div class="flex h-[35px] items-center justify-between rounded-[5px] border-[0.5px] border-gray-100 bg-gray-50 pl-2 pr-[13px] text-sm leading-5 text-black">
        <span class="truncate">{{ $value ?: '-' }}</span>
        @if ($chevron)
            <img src="{{ asset('mivent_assets/icons/field-chevron.svg') }}" alt="" class="h-[6.5px] w-[13px] -scale-y-100">
        @else
            <img src="{{ asset('mivent_assets/icons/lock-field.svg') }}" alt="" class="h-[15.75px] w-3">
        @endif
    </div>
</div>
