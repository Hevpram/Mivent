{{--
    Toast notifikasi (lihat resources/js/toast.js).
    Otomatis tampil dari session flash: success / error / warning / info,
    dan dari error validasi. Bisa juga dipanggil JS: window.toast('success', 'Judul', 'Pesan').
--}}
@php
    $toasts = [];
    foreach (['success' => 'Berhasil', 'error' => 'Gagal', 'warning' => 'Perhatian', 'info' => 'Informasi'] as $type => $judul) {
        if (session()->has($type)) {
            $toasts[] = ['type' => $type, 'title' => $judul, 'message' => session($type)];
        }
    }
    // Error validasi (semua bag) -> satu toast ringkas
    $jumlahError = collect($errors->getBags())->sum(fn ($bag) => $bag->count());
    if ($jumlahError > 0) {
        $pertama = collect($errors->getBags())->first(fn ($bag) => $bag->any())->first();
        $toasts[] = ['type' => 'error', 'title' => 'Periksa kembali isian Anda',
            'message' => $jumlahError > 1 ? $pertama . ' (+' . ($jumlahError - 1) . ' kesalahan lain)' : $pertama];
    }
    if (session()->has('dev_code')) {
        $toasts[] = ['type' => 'info', 'title' => 'Mode pengembangan', 'message' => 'Kode verifikasi: ' . session('dev_code')];
    }
@endphp

<div id="toast-root" class="pointer-events-none fixed right-6 top-24 z-[100] flex w-[380px] max-w-[calc(100vw-2rem)] flex-col gap-3"
     data-toasts='@json($toasts)' aria-live="polite"></div>

{{-- Template satu toast (di-clone oleh toast.js) --}}
<template id="toast-template">
    <div data-toast role="status"
         class="pointer-events-auto relative flex items-start gap-3 overflow-hidden rounded-2xl border border-l-[6px] bg-white py-4 pl-4 pr-10 shadow-[0_0_15px_rgba(64,103,159,0.25)]">
        <span data-toast-badge class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full">
            <svg data-icon="success" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>
            <svg data-icon="error" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
            <svg data-icon="warning" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 7v6M12 17h.01"/></svg>
            <svg data-icon="info" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><path d="M12 11v6M12 7h.01"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <p data-toast-title class="text-sm font-bold text-black"></p>
            <p data-toast-message class="mt-0.5 break-words text-xs text-gray-100"></p>
        </div>
        <button type="button" data-toast-close class="absolute right-3 top-3 text-gray-100 transition hover:text-black" aria-label="Tutup">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
        <span data-toast-bar class="absolute bottom-0 left-0 h-1 w-full"></span>
    </div>
</template>
