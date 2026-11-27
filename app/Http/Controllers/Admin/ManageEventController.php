<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Notifications\MiventNotification;
use App\Support\Files;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;

/**
 * Kelola Event (Admin Ormawa & Admin FMIPA untuk event buatannya):
 * daftar event, pendaftar per tahap, seleksi (terima/tolak), ekspor xlsx, sertifikat.
 */
class ManageEventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::managedBy($request->user())->with('organisasi')->withCount('registrations')->latest();

        if ($request->filled('q')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_selesai', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_mulai', '<=', $request->sampai);
        }
        // Filter status gabungan (verifikasi / tahap)
        match ($request->status) {
            'menunggu', 'revisi' => $query->where('status', $request->status),
            'pendaftaran', 'seleksi', 'selesai' => $query->where('status', 'disetujui')->when(
                $request->status === 'seleksi',
                fn ($q) => $q->whereIn('tahap', ['seleksi_berkas', 'seleksi_wawancara']),
                fn ($q) => $q->where('tahap', $request->status)
            ),
            default => null,
        };

        return view('admin.events.index', ['events' => $query->paginate(10)->withQueryString()]);
    }

    /** Halaman kelola pendaftar sebuah event. */
    public function show(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);
        $event->load('divisi', 'sertifikat', 'organisasi');

        $registrations = $event->registrations()->with('user.jurusan', 'divisi', 'divisi2', 'files')->get();
        // Pendaftar yang sedang diproses pada tahap ini
        $aktif = in_array($event->tahap, ['seleksi_berkas', 'seleksi_wawancara'], true)
            ? $registrations->where('status', $this->statusAktif($event))->values()
            : collect();

        return view('admin.events.manage', compact('event', 'registrations', 'aktif'));
    }

    /**
     * Aksi massal seleksi pada tahap saat ini:
     *  terima_semua / tolak_semua -> isi keputusan sementara semua pendaftar aktif
     *  reset                      -> kosongkan keputusan
     *  simpan                     -> terapkan keputusan ke status + kirim notifikasi
     */
    public function seleksi(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);
        if (! in_array($event->tahap, ['seleksi_berkas', 'seleksi_wawancara'], true)) {
            return back()->with('error', 'Seleksi hanya bisa dilakukan pada tahap seleksi.');
        }
        $request->validate(['aksi' => 'required|in:terima_semua,tolak_semua,reset,simpan']);
        $aktif = $event->registrations()->where('status', $this->statusAktif($event));

        if ($request->aksi !== 'simpan') {
            $nilai = ['terima_semua' => 'terima', 'tolak_semua' => 'tolak', 'reset' => null][$request->aksi];
            $aktif->update(['keputusan' => $nilai]);

            return back()->with('info', $nilai ? 'Keputusan diterapkan ke semua pendaftar. Klik "Simpan Hasil" untuk menyimpan.' : 'Keputusan direset.');
        }

        // Panitia: lolos berkas dulu lalu wawancara. Peserta: langsung diterima.
        $terima = $event->tahap === 'seleksi_berkas' && $event->isPanitia() ? 'lolos_berkas' : 'diterima';
        $jumlah = 0;
        DB::transaction(function () use ($aktif, $terima, $event, &$jumlah) {
            foreach ($aktif->whereNotNull('keputusan')->with('user')->get() as $reg) {
                $status = $reg->keputusan === 'terima' ? $terima : 'ditolak';
                $reg->update([
                    'status' => $status,
                    'keputusan' => null,
                    'divisi_diterima_id' => $status === 'diterima' ? ($reg->divisi_diterima_id ?? $reg->divisi_id) : $reg->divisi_diterima_id,
                ]);
                $reg->setRelation('event', $event);
                $reg->user->notify(new MiventNotification(
                    'Hasil seleksi ' . $event->nama_kegiatan,
                    'Status pendaftaran kamu: ' . $reg->statusLabel() . '.',
                    route('registrations.show', $reg),
                    $status === 'ditolak' ? 'danger' : 'success'
                ));
                $jumlah++;
            }
        });

        return back()->with('success', $jumlah ? "Hasil seleksi disimpan ($jumlah pendaftar)." : 'Belum ada keputusan yang bisa disimpan.');
    }

    /** Keputusan untuk 1 pendaftar (dari tabel atau halaman Lihat Berkas). */
    public function keputusan(Request $request, Event $event, Registration $registration)
    {
        $this->authorizeManage($request, $event);
        abort_unless($registration->event_id === $event->id, 404);
        if ($registration->status !== $this->statusAktif($event)) {
            return back()->with('error', 'Pendaftar ini tidak sedang dalam tahap seleksi.');
        }
        $data = $request->validate([
            'keputusan' => 'required|in:terima,tolak',
            'salah' => 'nullable|array',
            'salah.*' => 'integer',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($registration, $data, $event) {
            // Tahap berkas: tandai file yang salah beserta catatannya
            if ($event->tahap === 'seleksi_berkas') {
                $salah = $data['keputusan'] === 'tolak' ? array_map('intval', $data['salah'] ?? []) : [];
                foreach ($registration->files as $file) {
                    $isSalah = in_array($file->id, $salah, true);
                    $file->update(['salah' => $isSalah, 'catatan' => $isSalah ? ($data['catatan'][$file->id] ?? null) : null]);
                }
            }
            $registration->update(['keputusan' => $data['keputusan']]);
        });

        if ($event->tahap === 'seleksi_berkas' && $data['keputusan'] === 'tolak' && ! empty($data['salah'])) {
            $registration->user->notify(new MiventNotification(
                'Berkas Anda Salah', "Ada berkas yang perlu diperbaiki pada pendaftaran {$event->nama_kegiatan}.",
                route('registrations.show', $registration), 'danger'
            ));
        }

        return back()->with('success', 'Keputusan untuk ' . $registration->user->name . ' disimpan sementara. Klik "Simpan Hasil" untuk menerapkan.');
    }

    private function statusAktif(Event $event): string
    {
        return $event->tahap === 'seleksi_wawancara' ? 'lolos_berkas' : 'diproses';
    }

    /** Lanjut ke tahap berikutnya. */
    public function tahap(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);
        abort_unless($event->status === 'disetujui', 422);

        $belum = match ($event->tahap) {
            'seleksi_berkas' => $event->registrations()->where('status', 'diproses')->count(),
            'seleksi_wawancara' => $event->registrations()->where('status', 'lolos_berkas')->count(),
            default => 0,
        };
        if ($belum > 0) {
            return back()->with('error', "Masih ada $belum pendaftar yang belum diseleksi.");
        }

        $next = match ($event->tahap) {
            'pendaftaran' => 'seleksi_berkas',
            'seleksi_berkas' => $event->isPanitia() ? 'seleksi_wawancara' : 'selesai',
            'seleksi_wawancara' => 'selesai',
            default => null,
        };
        if (! $next) {
            return back();
        }
        $event->update(['tahap' => $next]);

        return back()->with('success', 'Tahap event diperbarui menjadi "' . Event::TAHAP[$next] . '".');
    }

    /** Unduh data pendaftar (.xlsx). */
    public function export(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);
        $path = tempnam(sys_get_temp_dir(), 'mivent') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($path);
        $writer->addRow(Row::fromValues(['No', 'Nama', 'NIM', 'Program Studi', 'Angkatan', 'Email', 'No. Telp', 'Sie/Divisi', 'Alasan', 'Status', 'Tanggal Daftar']));
        foreach ($event->registrations()->with('user.jurusan', 'divisi')->get() as $i => $r) {
            $writer->addRow(Row::fromValues([
                $i + 1, $r->user->name, $r->user->nim, $r->user->jurusan?->nama, $r->user->angkatan, $r->user->email,
                $r->phone, $r->divisi?->nama ?? '-', $r->alasan ?? '-', $r->statusLabel(), $r->created_at->format('d/m/Y H:i'),
            ]));
        }
        $writer->close();

        return response()->download($path, 'pendaftar-' . str($event->nama_kegiatan)->slug() . '.xlsx')->deleteFileAfterSend();
    }

    /** Template sertifikat (PDF) yang bisa diedit penyelenggara. */
    public function template(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);

        return Pdf::loadView('pdf.template-sertifikat', compact('event'))->setPaper('a4', 'landscape')
            ->download('template-sertifikat.pdf');
    }

    public function uploadSertifikat(Request $request, Event $event)
    {
        $this->authorizeManage($request, $event);
        abort_unless($event->tahap === 'selesai', 422);
        $request->validate([
            'registration_id' => ['nullable', \Illuminate\Validation\Rule::exists('registrations', 'id')->where('event_id', $event->id)->where('status', 'diterima')],
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [], ['file' => 'file sertifikat', 'registration_id' => 'penerima']);

        try {
            $path = Files::store($request->file('file'), 'sertifikat-event');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal mengunggah sertifikat. Coba lagi beberapa saat.');
        }
        $event->sertifikat()->create([
            'registration_id' => $request->registration_id,
            'judul' => 'Sertifikat ' . $event->nama_kegiatan,
            'file_sertif' => $path,
        ]);

        // Notifikasi ke peserta/panitia yang diterima
        $event->registrations()->where('status', 'diterima')->with('user')->get()->each(fn ($r) => $r->user->notify(
            new MiventNotification('Sertifikat tersedia', "Sertifikat {$event->nama_kegiatan} sudah bisa diunduh.", route('registrations.show', $r), 'success')
        ));

        return back()->with('success', 'Sertifikat Berhasil Diunggah.');
    }

    public function deleteSertifikat(Request $request, Event $event, int $sertifikat)
    {
        $this->authorizeManage($request, $event);
        $row = $event->sertifikat()->findOrFail($sertifikat);
        Files::delete($row->file_sertif);
        $row->delete();

        return back()->with('success', 'Sertifikat dihapus.');
    }

    /** Detail berkas satu pendaftar. */
    public function registrant(Request $request, Event $event, Registration $registration)
    {
        $this->authorizeManage($request, $event);
        abort_unless($registration->event_id === $event->id, 404);
        $registration->load('user.jurusan', 'divisi', 'divisi2', 'files');
        $ids = $event->registrations()->orderBy('id')->pluck('id')->values();
        $pos = $ids->search($registration->id);

        return view('admin.events.registrant', [
            'event' => $event,
            'registration' => $registration,
            'posisi' => $pos + 1,
            'total' => $ids->count(),
            'prevId' => $pos > 0 ? $ids[$pos - 1] : null,
            'nextId' => $pos < $ids->count() - 1 ? $ids[$pos + 1] : null,
            'bisaSeleksi' => $registration->status === $this->statusAktif($event) && in_array($event->tahap, ['seleksi_berkas', 'seleksi_wawancara'], true),
        ]);
    }

    /** Unduh semua berkas pendaftar dalam .zip */
    public function zip(Request $request, Event $event, Registration $registration)
    {
        $this->authorizeManage($request, $event);
        abort_unless($registration->event_id === $event->id, 404);
        $disk = \Illuminate\Support\Facades\Storage::disk(Files::disk());
        $path = tempnam(sys_get_temp_dir(), 'zip');
        $zip = new \ZipArchive();
        $zip->open($path, \ZipArchive::OVERWRITE);
        foreach ($registration->files as $i => $file) {
            if ($file->path && $disk->exists($file->path)) {
                $zip->addFromString(($i + 1) . '-' . str($file->label)->slug() . '.' . pathinfo($file->path, PATHINFO_EXTENSION), $disk->get($file->path));
            }
        }
        if ($zip->numFiles === 0) {
            $zip->addFromString('README.txt', 'Pendaftar ini belum memiliki berkas yang tersimpan.');
        }
        $zip->close();

        return response()->download($path, 'berkas-' . str($registration->user->name)->slug() . '.zip')->deleteFileAfterSend();
    }

    /** Bukti pendaftaran (PDF) dari sisi penyelenggara. */
    public function bukti(Request $request, Event $event, Registration $registration)
    {
        $this->authorizeManage($request, $event);
        abort_unless($registration->event_id === $event->id, 404);
        $registration->load('event.organisasi', 'divisi', 'divisi2', 'files', 'user.jurusan');

        return Pdf::loadView('pdf.bukti', ['r' => $registration])->download('bukti-pendaftaran-' . $registration->id . '.pdf');
    }

    private function authorizeManage(Request $request, Event $event): void
    {
        abort_unless($event->canBeManagedBy($request->user()), 403);
    }
}
