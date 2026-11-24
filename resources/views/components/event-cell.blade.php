@props(['event'])
<div class="flex w-[280px] shrink-0 items-center gap-3 text-left">
    <img src="{{ $event->posterUrl() }}" alt="" class="size-[52px] shrink-0 rounded-[5px] object-cover">
    <div class="flex min-w-0 flex-1 flex-col">
        <p class="text-sm font-bold text-black">{{ $event->nama_kegiatan }}</p>
        <p class="w-[232px] max-w-full text-xs text-black">{{ $event->lokasi }}</p>
    </div>
</div>
