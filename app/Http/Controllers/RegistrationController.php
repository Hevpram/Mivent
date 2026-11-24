<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use App\Notifications\MiventNotification;
use App\Support\Files;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Pendaftaran event oleh mahasiswa + halaman Event Diikuti.
 *  - Event PANITIA : pilih sie/divisi, alasan, unggah dokumen wajib (sertifikat) yang dicentang penyelenggara.
 *  - Event PESERTA : hanya unggah berkas sesuai syarat yang ditandai "wajib unggah berkas".
 */
class RegistrationController extends Controller
{
    public function create(Request $request, Event $event)
    {
        if ($redirect = $this->guard($request, $event)) {
            return $redirect;
        }
        $event->load('divisi', 'syarat', 'organisasi');

        return view('registrations.create', ['event' => $event, 'user' => $request->user()->load('jurusan')]);
    }

    public function store(Request $request, Event $event)
    {
        if ($redirect = $this->guard($request, $event)) {
            return $redirect;
        }
        $user = $request->user();
        $file = 'file|mimes:pdf,jpg,jpeg,png|max:2048';

        // Aturan dasar untuk semua peran
        $rules = [
            'phone' => ['required', 'regex:/^[0-9]{10,13}$/'],
            'setuju' => 'accepted',
        ];
        $names = ['phone' => 'nomor telepon'];

        if ($event->isPanitia()) {
            $rules['divisi_id'] = ['required', Rule::exists('event_divisi', 'id')->where('event_id', $event->id)];
            // Sie pilihan 2 opsional, tidak boleh sama dengan pilihan 1
            $rules['divisi2_id'] = ['nullable', 'different:divisi_id', Rule::exists('event_divisi', 'id')->where('event_id', $event->id)];
            $rules['alasan'] = 'required|string|max:1000';
            $names += ['divisi_id' => 'sie pilihan 1', 'divisi2_id' => 'sie pilihan 2', 'alasan' => 'portofolio / alasan'];
            // Sertifikat: wajib upload bila profil belum punya file-nya
            foreach ($event->requiredSertifikat() as $field => $label) {
                $rules["berkas.$field"] = ($user->{$field} ? 'nullable|' : 'required|') . $file;
                $names["berkas.$field"] = $label;
            }
            if ($event->req_instagram) {
                $rules['berkas.instagram'] = 'required|' . $file;
                $names['berkas.instagram'] = 'Bukti Follow Instagram';
            }
        } else {
            foreach ($event->syarat->where('butuh_berkas', true) as $syarat) {
                $rules["syarat.{$syarat->id}"] = 'required|' . $file;
                $names["syarat.{$syarat->id}"] = 'berkas "' . $syarat->deskripsi . '"';
            }
        }

        $request->validate($rules, [
            'phone.regex' => 'Nomor telepon harus 10-13 digit angka.',
            'setuju.accepted' => 'Centang pernyataan bahwa data kamu sudah benar.',
        ], $names);

        try {
            $registration = $this->saveRegistration($request, $event, $user);
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Pendaftaran gagal disimpan. Silakan coba lagi.');
        }

        // Beri tahu pengelola event
        $event->creator?->notify(new MiventNotification(
            'Pendaftar baru', "{$user->name} mendaftar ke {$event->nama_kegiatan}.", route('manage.show', $event)
        ));

        // flash "registered" -> modal "Pendaftaran Berhasil Terkirim" di halaman detail
        return redirect()->route('registrations.show', $registration)->with('registered', true)->with('success', 'Pendaftaran berhasil dikirim!');
    }

    /** Simpan pendaftaran + berkas dalam satu transaksi. */
    private function saveRegistration(Request $request, Event $event, $user): Registration
    {
        return DB::transaction(function () use ($request, $event, $user) {
            $reg = Registration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'divisi_id' => $event->isPanitia() ? $request->divisi_id : null,
                'divisi2_id' => $event->isPanitia() ? $request->divisi2_id : null,
                'phone' => $request->phone,
                'alasan' => $event->isPanitia() ? $request->alasan : null,
                'status' => 'diproses',
            ]);

            if ($event->isPanitia()) {
                foreach ($event->requiredSertifikat() as $field => $label) {
                    // Pakai file baru bila diunggah, kalau tidak pakai file dari profil
                    $path = Files::store($request->file("berkas.$field"), 'berkas') ?? $user->{$field};
                    $reg->files()->create(['label' => $label, 'path' => $path]);
                }
                if ($event->req_instagram) {
                    $reg->files()->create(['label' => 'Bukti Follow Instagram', 'path' => Files::store($request->file('berkas.instagram'), 'berkas')]);
                }
            } else {
                foreach ($event->syarat->where('butuh_berkas', true) as $syarat) {
                    $reg->files()->create([
                        'syarat_id' => $syarat->id,
                        'label' => $syarat->deskripsi,
                        'path' => Files::store($request->file("syarat.{$syarat->id}"), 'berkas'),
                    ]);
                }
            }

            return $reg;
        });
    }

    /** "Update Kesalahan Berkas": mahasiswa mengganti berkas yang ditandai salah. */
    public function updateBerkas(Request $request, Registration $registration)
    {
        abort_unless($registration->user_id === $request->user()->id, 403);
        $salah = $registration->files()->where('salah', true)->get();
        if ($salah->isEmpty()) {
            return back()->with('info', 'Tidak ada berkas yang perlu diperbaiki.');
        }

        $rules = [];
        $names = [];
        foreach ($salah as $file) {
            $rules["berkas.{$file->id}"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $names["berkas.{$file->id}"] = $file->label;
        }
        $request->validate($rules, [], $names);

        try {
            DB::transaction(function () use ($request, $salah, $registration) {
                foreach ($salah as $file) {
                    $file->update(['path' => Files::store($request->file("berkas.{$file->id}"), 'berkas'), 'salah' => false, 'catatan' => null]);
                }
                // Berkas sudah diperbaiki -> keputusan direset agar diperiksa ulang
                $registration->update(['keputusan' => null]);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Gagal mengunggah berkas. Silakan coba lagi.');
        }

        $registration->event->creator?->notify(new MiventNotification(
            'Berkas diperbarui', "{$request->user()->name} memperbarui berkas pendaftaran {$registration->event->nama_kegiatan}.",
            route('manage.registrant', [$registration->event, $registration])
        ));

        return back()->with('success', 'Berkas berhasil diperbarui dan akan diperiksa ulang.')->with('berkas_diperbarui', true);
    }

    /** Cek event bisa didaftar & mahasiswa belum terdaftar. */
    private function guard(Request $request, Event $event)
    {
        if (! $event->isOpen()) {
            return redirect()->route('events.show', $event)->with('error', 'Pendaftaran event ini sudah ditutup.');
        }
        $existing = $event->registrations()->where('user_id', $request->user()->id)->first();
        if ($existing) {
            return redirect()->route('registrations.show', $existing)->with('info', 'Kamu sudah terdaftar di event ini.');
        }

        return null;
    }

    /** Event Diikuti: cari + filter status. */
    public function index(Request $request)
    {
        $query = $request->user()->registrations()->with('event.organisasi', 'event.skp', 'divisi', 'divisiDiterima')->latest();

        if ($request->filled('q')) {
            $kata = '%' . $request->q . '%';
            $query->whereHas('event', fn ($e) => $e->where('judul', 'like', $kata)
                ->orWhere('nama_kegiatan', 'like', $kata)
                ->orWhereHas('organisasi', fn ($o) => $o->where('nama', 'like', $kata)));
        }
        $semua = $query->get();
        // Filter status dihitung dari label (gabungan status pendaftaran + tahap event)
        if ($request->filled('status')) {
            $semua = $semua->filter(fn ($r) => $r->statusLabel() === $request->status)->values();
        }
        // Paginasi manual 10 data per halaman
        $page = max((int) $request->input('page', 1), 1);
        $registrations = new \Illuminate\Pagination\LengthAwarePaginator(
            $semua->forPage($page, 10)->values(), $semua->count(), 10, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('registrations.index', compact('registrations'));
    }

    public function show(Request $request, Registration $registration)
    {
        abort_unless($registration->user_id === $request->user()->id, 403);
        $registration->load('event.organisasi', 'event.kategori', 'event.timelines', 'event.skp', 'divisi', 'divisi2', 'divisiDiterima', 'files', 'user');

        return view('registrations.show', ['registration' => $registration, 'event' => $registration->event]);
    }

    /** Bukti pendaftaran dalam format PDF. */
    public function pdf(Request $request, Registration $registration)
    {
        abort_unless($registration->user_id === $request->user()->id, 403);
        $registration->load('event.organisasi', 'divisi', 'divisi2', 'files', 'user.jurusan');

        return Pdf::loadView('pdf.bukti', ['r' => $registration])
            ->download('bukti-pendaftaran-' . $registration->id . '.pdf');
    }
}
