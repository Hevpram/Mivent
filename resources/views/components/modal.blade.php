{{--
    Modal sederhana. Buka: tombol [data-modal-open="id"]; tutup: [data-modal-close], klik latar, atau Esc.
    :open="true" -> langsung tampil (mis. setelah validasi gagal). Logika di resources/js/ui.js.
--}}
@props(['id', 'title' => null, 'subtitle' => null, 'icon' => null, 'open' => false, 'width' => 'max-w-lg'])
<div data-modal="{{ $id }}" @if ($open) data-modal-autoopen @endif
     class="fixed inset-0 z-[90] hidden items-center justify-center bg-black/40 p-4 backdrop-blur-[2px]" role="dialog" aria-modal="true">
    <div data-modal-panel class="relative max-h-[92vh] w-full {{ $width }} overflow-y-auto rounded-[20px] bg-white p-6 shadow-[0_0_15px_rgba(64,103,159,0.50)]">
        @if ($title)
            <div class="mb-5 flex items-start gap-3 pr-8">
                @if ($icon)
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-200 text-white">
                        <x-ui-icon :name="$icon" class="h-5 w-5" />
                    </span>
                @endif
                <div>
                    <h2 class="text-lg font-bold text-primary-200">{{ $title }}</h2>
                    @if ($subtitle)<p class="text-xs text-black">{{ $subtitle }}</p>@endif
                </div>
            </div>
        @endif
        <button type="button" data-modal-close class="absolute right-4 top-4 rounded-md p-1 text-gray-100 transition hover:bg-red-50 hover:text-red-100" aria-label="Tutup">
            <x-ui-icon name="x" class="h-5 w-5" />
        </button>
        {{ $slot }}
    </div>
</div>
