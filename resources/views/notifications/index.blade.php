{{-- Notifikasi (semua role) --}}
@php
    $tipe = [
        'success' => ['check-circle', 'text-green-150', 'border-green-100 bg-green-50 text-green-150'],
        'danger' => ['alert', 'text-red-100', 'border-red-100 bg-red-50 text-red-100'],
        'warning' => ['alert', 'text-yellow-100', 'border-yellow-100 bg-yellow-50 text-yellow-100'],
        'info' => ['info', 'text-primary-200', 'border-primary-100 bg-blue-50 text-primary-200'],
    ];
    $isAdmin = ! auth()->user()->isMahasiswa();
@endphp
<x-dynamic-component :component="$isAdmin ? 'admin-layout' : 'layout'" title="Notifikasi">
    <div @class(['mx-auto max-w-5xl px-6 py-10' => ! $isAdmin])>
        <div class="flex items-start justify-between">
            <div>
                <h1 data-domino class="text-gradient-mivent w-fit text-4xl font-bold">Notifikasi</h1>
                <p data-domino class="mt-1 text-lg">Pesan dan pembaruan seputar event kamu.</p>
            </div>
            @if ($notifications->contains(fn ($n) => ! $n->read_at))
                <form data-domino method="POST" action="{{ route('notifications.readAll') }}">@csrf
                    <button class="flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-primary-200 shadow-[0_0_8px_rgba(64,103,159,0.45)] transition hover:-translate-y-0.5 hover:bg-primary-200 hover:text-white"><x-ui-icon name="check" class="h-4 w-4" /> Tandai Semua Dibaca</button>
                </form>
            @endif
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($notifications as $n)
                @php [$ic, $icCls, $badge] = $tipe[$n->data['tipe'] ?? 'info'] ?? $tipe['info']; @endphp
                <div data-domino @class(['flex items-start gap-3 rounded-xl border-l-4 bg-white p-4 shadow-[0_0_10px_rgba(64,103,159,0.3)] transition hover:-translate-y-0.5', 'border-primary-200' => ! $n->read_at, 'border-gray-50 opacity-80' => $n->read_at])>
                    <x-ui-icon :name="$ic" class="mt-0.5 h-5 w-5 shrink-0 {{ $icCls }}" />
                    <div class="min-w-0 flex-1">
                        <span class="inline-block rounded border px-2 py-0.5 text-xs font-semibold {{ $badge }}">{{ $n->data['judul'] ?? 'Notifikasi' }}</span>
                        <p class="mt-1 text-xs">{{ $n->data['pesan'] ?? '' }}</p>
                        <p class="mt-1 text-xs text-gray-100">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        @if (! empty($n->data['url']))
                            <a href="{{ route('notifications.open', $n->id) }}" class="rounded bg-primary-200 px-3 py-1 text-xs font-semibold text-white transition hover:bg-primary-150">Lihat Detail</a>
                        @endif
                        @unless ($n->read_at)
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">@csrf<button class="rounded p-1 text-gray-100 transition hover:bg-green-50 hover:text-green-150" aria-label="Tandai dibaca" title="Tandai dibaca"><x-ui-icon name="check" class="h-5 w-5" /></button></form>
                        @endunless
                        <form method="POST" action="{{ route('notifications.destroy', $n->id) }}" data-confirm="Hapus notifikasi ini?">@csrf @method('DELETE')<button class="rounded p-1 text-gray-100 transition hover:bg-red-50 hover:text-red-100" aria-label="Hapus" title="Hapus"><x-ui-icon name="trash" class="h-5 w-5" /></button></form>
                    </div>
                </div>
            @empty
                <div data-domino class="card py-14 text-center text-sm text-gray-100"><x-ui-icon name="bell" class="mx-auto mb-2 h-8 w-8" />Belum ada notifikasi.</div>
            @endforelse
        </div>
        <div class="mt-4 flex justify-end">{{ $notifications->links('partials.pagination') }}</div>
    </div>
</x-dynamic-component>
