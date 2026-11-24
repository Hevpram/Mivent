{{-- Penampil berkas: zoom, rotasi, unduh, layar penuh. Isi diganti lewat [data-file-switch]. --}}
@props(['id'])
<div id="{{ $id }}" data-viewer {{ $attributes->merge(['class' => 'overflow-hidden rounded-[20px] bg-gray-50 shadow-[0_0_15px_rgba(64,103,159,0.35)]']) }}>
    <div class="flex items-center gap-3 bg-white px-4 py-2 text-xs">
        <button type="button" data-viewer-out class="rounded p-1 transition hover:bg-blue-50" aria-label="Perkecil"><x-ui-icon name="zoom-out" class="h-4 w-4" /></button>
        <span data-viewer-zoom class="w-10 text-center">100%</span>
        <button type="button" data-viewer-in class="rounded p-1 transition hover:bg-blue-50" aria-label="Perbesar"><x-ui-icon name="zoom-in" class="h-4 w-4" /></button>
        <button type="button" data-viewer-rotate class="rounded p-1 transition hover:bg-blue-50" aria-label="Putar"><x-ui-icon name="rotate" class="h-4 w-4" /></button>
        <span data-viewer-title class="flex-1 truncate font-semibold text-primary-200"></span>
        <a data-viewer-download href="#" target="_blank" rel="noopener" download class="flex items-center gap-1 rounded px-2 py-1 transition hover:bg-blue-50 hover:text-primary-200"><x-ui-icon name="download" class="h-4 w-4" /> Unduh Berkas Ini</a>
        <button type="button" onclick="this.closest('[data-viewer]').requestFullscreen?.()" class="rounded p-1 transition hover:bg-blue-50" aria-label="Layar penuh"><x-ui-icon name="zoom-in" class="h-4 w-4" /></button>
        {{ $actions ?? '' }}
    </div>
    <div class="flex min-h-[420px] items-center justify-center overflow-auto p-6">
        <div data-viewer-target class="w-full origin-center transition-transform duration-300">
            <p class="text-center text-sm text-gray-100">Pilih berkas untuk ditampilkan.</p>
        </div>
    </div>
</div>
