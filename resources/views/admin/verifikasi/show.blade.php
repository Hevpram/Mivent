{{-- Detail Verifikasi Event (Admin FMIPA) --}}
@php $sik = \App\Support\Files::url($event->surat_izin_kegiatan); @endphp
<x-admin-layout title="Verifikasi {{ $event->nama_kegiatan }}">
    @include('partials.event-body', ['registration' => null, 'back' => route('fmipa.verifikasi.index'), 'backLabel' => 'Kembali Ke Verifikasi Event'])

    <div data-domino class="card mt-6 p-6">
        <h2 class="flex items-center gap-3 text-xl font-bold text-primary-200"><x-ui-icon name="file" class="h-6 w-6" /> Surat Izin Kegiatan</h2>
        @if ($sik)
            <iframe src="{{ $sik }}" title="Surat Izin Kegiatan" class="mt-4 h-[640px] w-full rounded-lg border border-gray-50 bg-white"></iframe>
            <a href="{{ $sik }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-primary-200 hover:underline"><x-ui-icon name="download" class="h-4 w-4" /> Buka / unduh SIK</a>
        @else
            <p class="mt-4 rounded-lg bg-yellow-50 px-4 py-6 text-center text-sm">Surat Izin Kegiatan belum diunggah.</p>
        @endif
        @if ($event->catatan_revisi)
            <p class="mt-4 rounded-lg border border-yellow-100 bg-yellow-50 px-4 py-3 text-sm"><b>Catatan revisi sebelumnya:</b> {{ $event->catatan_revisi }}</p>
        @endif
    </div>

    <div data-domino class="card mt-6 flex items-center justify-between gap-6 px-6 py-4">
        <p class="text-sm">{{ $event->status === 'disetujui' ? 'Event ini sudah diverifikasi dan tayang.' : 'Sebelum disetujui, cek kembali kelengkapan dan kesesuaian persyaratan administrasi.' }}</p>
        @if ($event->status !== 'disetujui')
            <div class="flex shrink-0 gap-3">
                <button type="button" data-modal-open="revisi" class="btn-outline-nav">Revisi Event</button>
                <button type="button" data-modal-open="setujui" class="btn-fill-nav">Verifikasi Event <x-ui-icon name="arrow-right" class="h-5 w-5" /></button>
            </div>
        @else
            <span class="rounded border border-green-100 bg-green-50 px-3 py-1 text-xs text-green-150">Sudah Verifikasi</span>
        @endif
    </div>

    @if ($event->status !== 'disetujui')
        <x-modal id="revisi" title="Kirim Revisi" subtitle="Jelaskan bagian yang perlu diperbaiki penyelenggara." icon="pencil" :open="$errors->has('catatan_revisi')">
            <form method="POST" action="{{ route('fmipa.verifikasi.revise', $event) }}" data-once>
                @csrf
                <textarea name="catatan_revisi" rows="5" required maxlength="2000" class="input" placeholder="Contoh: SIK belum ditandatangani pembina.">{{ old('catatan_revisi') }}</textarea>
                <x-field-error name="catatan_revisi" />
                <div class="mt-4 flex justify-end gap-3"><button type="button" data-modal-close class="btn-outline-nav">Batal</button><button type="submit" class="btn-fill-nav">Kirim Revisi</button></div>
            </form>
        </x-modal>
        <x-modal id="setujui" title="Setujui Event" subtitle="Pilih kategori SKP. Event akan langsung tayang di Mivent." icon="check-circle" :open="$errors->has('skp_id')">
            <form method="POST" action="{{ route('fmipa.verifikasi.approve', $event) }}" data-once>
                @csrf
                <label for="skp_id" class="text-sm font-medium">Kategori SKP <span class="text-red-100">*</span></label>
                <select id="skp_id" name="skp_id" required class="input mt-2 cursor-pointer">
                    <option value="">Pilih Kategori SKP</option>
                    @foreach ($skp as $s)<option value="{{ $s->id }}" @selected(old('skp_id', $event->skp_id) == $s->id)>{{ $s->nama }}</option>@endforeach
                </select>
                <x-field-error name="skp_id" />
                <div class="mt-4 flex justify-end gap-3"><button type="button" data-modal-close class="btn-outline-nav">Batal</button><button type="submit" class="btn-fill-nav">Setujui &amp; Tayangkan</button></div>
            </form>
        </x-modal>
    @endif
</x-admin-layout>
