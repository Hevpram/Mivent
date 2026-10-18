@props([
    'label' => 'Rentang Tanggal',
    'required' => true,
    'start' => null,
    'end' => null,
    'month' => null,
    'nameStart' => 'tanggal_mulai',
    'nameEnd' => 'tanggal_selesai',
])

<div {{ $attributes->merge(['class' => 'date-range-calendar relative w-full font-poppins']) }}
     data-start="{{ $start }}" data-end="{{ $end }}" data-month="{{ $month }}">
    {{-- @if ($label)
        <span class="mb-2 block text-base font-medium text-black">
            {{ $label }}@if ($required) <span class="text-red-100">*</span>@endif
        </span>
    @endif --}}

    {{-- Field tanggal (button) --}}
    <div class="grid grid-cols-2 gap-6">
        <button type="button" data-active="false" aria-haspopup="dialog" aria-expanded="false"
                class="drc-field-start flex h-12 w-full items-center gap-3 rounded-lg border border-gray-100 bg-white px-3 text-left shadow-[0_0_8px_rgba(64,103,159,0.25)] transition focus:border-primary-200 focus:shadow-[0_0_8px_rgba(64,103,159,0.5)] focus:outline-none data-[active=true]:border-primary-200 data-[active=true]:shadow-[0_0_8px_rgba(64,103,159,0.5)]">
            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.5 15C1.0875 15 0.734375 14.8531 0.440625 14.5594C0.146875 14.2656 0 13.9125 0 13.5V3C0 2.5875 0.146875 2.23438 0.440625 1.94062C0.734375 1.64687 1.0875 1.5 1.5 1.5H2.25V0H3.75V1.5H9.75V0H11.25V1.5H12C12.4125 1.5 12.7656 1.64687 13.0594 1.94062C13.3531 2.23438 13.5 2.5875 13.5 3V13.5C13.5 13.9125 13.3531 14.2656 13.0594 14.5594C12.7656 14.8531 12.4125 15 12 15H1.5V15M1.5 13.5H12V13.5V13.5V6H1.5V13.5V13.5V13.5V13.5M1.5 4.5H12V3V3V3H1.5V3V3V4.5V4.5M1.5 4.5V3V3V3V3V3V3V4.5V4.5V4.5" fill="#9CA3AF"/>
            </svg>
            <span class="drc-text-start truncate text-sm text-black data-[empty=true]:text-gray-100" data-empty="true" data-placeholder="Tanggal mulai">Tanggal mulai</span>
        </button>
        <button type="button" data-active="false" aria-haspopup="dialog" aria-expanded="false"
                class="drc-field-end flex h-12 w-full items-center gap-3 rounded-lg border border-gray-100 bg-white px-3 text-left shadow-[0_0_8px_rgba(64,103,159,0.25)] transition focus:border-primary-200 focus:shadow-[0_0_8px_rgba(64,103,159,0.5)] focus:outline-none data-[active=true]:border-primary-200 data-[active=true]:shadow-[0_0_8px_rgba(64,103,159,0.5)]">
                <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.625 12C8.1 12 7.65625 11.8188 7.29375 11.4563C6.93125 11.0938 6.75 10.65 6.75 10.125C6.75 9.6 6.93125 9.15625 7.29375 8.79375C7.65625 8.43125 8.1 8.25 8.625 8.25C9.15 8.25 9.59375 8.43125 9.95625 8.79375C10.3188 9.15625 10.5 9.6 10.5 10.125C10.5 10.65 10.3188 11.0938 9.95625 11.4563C9.59375 11.8188 9.15 12 8.625 12V12M1.5 15C1.0875 15 0.734375 14.8531 0.440625 14.5594C0.146875 14.2656 0 13.9125 0 13.5V3C0 2.5875 0.146875 2.23438 0.440625 1.94062C0.734375 1.64687 1.0875 1.5 1.5 1.5H2.25V0H3.75V1.5H9.75V0H11.25V1.5H12C12.4125 1.5 12.7656 1.64687 13.0594 1.94062C13.3531 2.23438 13.5 2.5875 13.5 3V13.5C13.5 13.9125 13.3531 14.2656 13.0594 14.5594C12.7656 14.8531 12.4125 15 12 15H1.5V15M1.5 13.5H12V13.5V13.5V6H1.5V13.5V13.5V13.5V13.5M1.5 4.5H12V3V3V3H1.5V3V3V4.5V4.5M1.5 4.5V3V3V3V3V3V3V4.5V4.5V4.5" fill="#9CA3AF"/>
                </svg>
                <span class="drc-text-end truncate text-sm text-black data-[empty=true]:text-gray-100" data-empty="true" data-placeholder="Tanggal selesai">Tanggal selesai</span>
        </button>
    </div>

    <div class="drc-popover absolute left-0 top-full z-30 mt-2 hidden w-[320px] select-none rounded-2xl border-b-4 border-primary-150 bg-white p-5 shadow-[0_0px_8px_rgba(64,103,159,0.35)]"
         role="dialog" aria-label="Pilih rentang tanggal">
        {{-- Header --}}
        <div class="mb-4 flex items-center justify-between">
            <p class="drc-label text-lg font-bold text-primary-200" aria-live="polite"></p>
            <div class="flex items-center gap-2">
                <button type="button" class="drc-prev flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-200 transition hover:bg-gray-50" aria-label="Bulan sebelumnya">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                </button>
                <button type="button" class="drc-next flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-primary-200 transition hover:bg-gray-50" aria-label="Bulan berikutnya">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </button>
            </div>
        </div>

        <div class="mb-1.5 grid grid-cols-7 gap-1.5 text-center text-sm font-bold text-primary-200">
            <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Se</span>
        </div>

        {{-- Grid tanggal (diisi oleh JavaScript) --}}
        <div class="drc-grid grid grid-cols-7 gap-1.5" role="grid"></div>
    </div>

    <input type="hidden" class="drc-start" name="{{ $nameStart }}" value="{{ $start }}">
    <input type="hidden" class="drc-end" name="{{ $nameEnd }}" value="{{ $end }}">
</div>
