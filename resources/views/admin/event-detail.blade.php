{{-- Detail Event dilihat Admin (Ormawa / FMIPA) --}}
<x-admin-layout :title="$event->nama_kegiatan">
    @include('partials.event-body', ['back' => url()->previous() !== url()->current() ? url()->previous() : route('events.index'), 'backLabel' => 'Kembali'])
    @if ($event->canBeManagedBy(auth()->user()))
        <div data-domino class="mt-10 flex justify-center">
            <a href="{{ route('manage.show', $event) }}" class="inline-flex w-[420px] items-center justify-center gap-3 rounded-lg bg-primary-150 py-3 text-sm font-semibold text-white shadow-[0_0_8px_rgba(64,103,159,0.5)] transition-all duration-300 hover:bg-primary-200">
                Kelola Event Ini <x-ui-icon name="arrow-right" class="h-5 w-5" />
            </a>
        </div>
    @endif
</x-admin-layout>
