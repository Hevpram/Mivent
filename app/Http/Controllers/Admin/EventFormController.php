<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Kategori;
use App\Models\Organisasi;
use App\Models\Skp;
use App\Models\User;
use App\Notifications\MiventNotification;
use App\Support\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Mews\Purifier\Facades\Purifier;

/**
 * Form Ajukan Event (Admin Ormawa) / Buat Event (Admin FMIPA).
 *
 * Aturan AND langkah 1: Penyelenggara DAN Peran wajib dipilih, baru modul langkah 2 muncul.
 *  - Peran "panitia": Timeline Oprec + Syarat Pendaftaran + Kebutuhan Sie + Dokumen wajib pendaftar.
 *  - Peran "peserta": Timeline Pendaftaran + Syarat Pendaftaran saja.
 * Aturan yang sama divalidasi di server (method rules()).
 *
 * Event Admin Ormawa -> status "menunggu" (perlu verifikasi Admin FMIPA).
 * Event Admin FMIPA  -> langsung "disetujui" (tayang tanpa verifikasi, revisi dosen).
 */
class EventFormController extends Controller
{
    public function create(Request $request)
    {
        return view('admin.events.form', $this->formData($request, new Event(['peran' => null])));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $this->validated($request, null);

        try {
            $event = DB::transaction(function () use ($request, $user, $data) {
            $event = new Event($data['event']);
            $event->user_id = $user->id;
            $event->tahap = 'pendaftaran';
            $event->poster = Files::store($request->file('poster'), 'poster');
            $event->surat_izin_kegiatan = Files::store($request->file('sik'), 'sik');

            if ($user->isFmipa()) {
                // Revisi dosen: event Admin FMIPA langsung tayang
                $event->status = 'disetujui';
                $event->verified_by = $user->id;
                $event->verified_at = now();
            } else {
                $event->status = 'menunggu';
            }
            $event->save();
            $this->syncChildren($event, $data);

            return $event;
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Event gagal disimpan. Periksa berkas lalu coba lagi.');
        }

        if ($user->isOrmawa()) {
            // Beri tahu semua Admin FMIPA ada pengajuan baru
            User::where('role', User::FMIPA)->get()->each->notify(new MiventNotification(
                'Pengajuan event baru', "{$event->organisasi->nama} mengajukan {$event->nama_kegiatan}.", route('fmipa.verifikasi.show', $event)
            ));

            return redirect()->route('manage.index')->with('success', 'Pengajuan Event Berhasil Dikirim. Menunggu verifikasi Admin FMIPA.');
        }

        return redirect()->route('manage.index')->with('success', 'Event berhasil dipublikasikan dan langsung tayang.');
    }

    public function edit(Request $request, Event $event)
    {
        $this->authorizeEdit($request, $event);
        $event->load('timelines', 'syarat', 'divisi');

        return view('admin.events.form', $this->formData($request, $event));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEdit($request, $event);
        $user = $request->user();
        $data = $this->validated($request, $event);

        try {
            DB::transaction(function () use ($request, $event, $data, $user) {
            $event->fill($data['event']);
            if ($request->hasFile('poster')) {
                Files::delete($event->poster);
                $event->poster = Files::store($request->file('poster'), 'poster');
            }
            if ($request->hasFile('sik')) {
                Files::delete($event->surat_izin_kegiatan);
                $event->surat_izin_kegiatan = Files::store($request->file('sik'), 'sik');
            }
            if ($user->isOrmawa()) {
                // Hasil perbaikan dikirim ulang ke antrean verifikasi
                $event->status = 'menunggu';
            }
            $event->save();
            $this->syncChildren($event, $data);
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Perubahan event gagal disimpan. Silakan coba lagi.');
        }

        return redirect()->route('manage.index')->with('success', $user->isOrmawa()
            ? 'Perbaikan event berhasil dikirim ulang untuk verifikasi.'
            : 'Event berhasil diperbarui.');
    }

    /** Ormawa hanya boleh mengedit event miliknya yang menunggu / perlu revisi. */
    private function authorizeEdit(Request $request, Event $event): void
    {
        $user = $request->user();
        abort_unless($event->canBeManagedBy($user), 403);
        if ($user->isOrmawa() && $event->status === 'disetujui') {
            abort(403, 'Event yang sudah disetujui tidak bisa diubah.');
        }
    }

    private function formData(Request $request, Event $event): array
    {
        $user = $request->user();
        $organisasi = $user->isOrmawa()
            ? Organisasi::whereKey($user->organisasi_id)->get()     // ormawa: hanya organisasinya
            : Organisasi::orderByDesc('is_fakultas')->orderBy('id')->get(); // fmipa: semua unit

        return [
            'event' => $event,
            'organisasi' => $organisasi,
            'kategori' => Kategori::orderBy('id')->get(),
            'skp' => Skp::orderBy('id')->get(),
        ];
    }

    /** Validasi server dengan aturan bersyarat berdasarkan peran (AND rule). */
    private function validated(Request $request, ?Event $event): array
    {
        $user = $request->user();
        // Nama radio mengikuti form asli: "Penyelenggara" & "Peran"
        $isPanitia = $request->input('Peran') === 'panitia';
        $creating = $event === null;

        $orgRule = $user->isOrmawa()
            ? ['required', Rule::in([$user->organisasi_id])]
            : ['required', 'exists:organisasi,id'];
        $phone = ['regex:/^[0-9]{10,13}$/'];

        $rules = [
            'Penyelenggara' => $orgRule,
            'Peran' => ['required', Rule::in(array_keys(Event::PERAN))],
            'judul' => 'required|string|max:150',
            'nama_kegiatan' => 'required|string|max:150',
            'kategori_id' => 'required|exists:kategori,id',
            'kategori_pendaftar' => ['required', Rule::in(array_keys(Event::KATEGORI_PENDAFTAR))],
            'narahubung_telp' => array_merge(['required'], $phone),
            'narahubung_nama' => 'required|string|max:100',
            'narahubung2_telp' => array_merge(['required'], $phone),
            'narahubung2_nama' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lokasi' => 'required|string|max:150',
            'deskripsi_singkat' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:20000',
            'poster' => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png|max:4096',
            // SIK wajib untuk ormawa saat pengajuan baru; Admin FMIPA opsional
            'sik' => ($creating && $user->isOrmawa() ? 'required' : 'nullable') . '|file|mimes:pdf|max:5120',
            // Modul langkah 2 (dipakai panitia & peserta)
            'timelines' => 'required|array|min:1',
            'timelines.*.nama' => 'required|string|max:100',
            'timelines.*.mulai' => 'required|date',
            'timelines.*.selesai' => 'required|date|after_or_equal:timelines.*.mulai',
            'syarat' => 'required|array|min:1',
            'syarat.*.deskripsi' => 'required|string|max:255',
            'syarat.*.butuh_berkas' => 'nullable|boolean',
        ];

        if ($isPanitia) {
            // Modul khusus panitia
            $rules += [
                'divisi' => 'required|array|min:1',
                'divisi.*.id' => 'nullable|integer',
                'divisi.*.nama' => 'required|string|max:100',
                'divisi.*.kuota' => 'required|integer|min:1|max:500',
                'divisi.*.deskripsi' => 'required|string|max:1000',
                'dokumen' => 'nullable|array',
                'dokumen.*' => Rule::in(['req_lkmm', 'req_pkkmb_fakultas', 'req_pkkmb_jurusan', 'req_pengalaman', 'req_instagram']),
            ];
        }
        if ($user->isFmipa()) {
            $rules['skp_id'] = 'required|exists:skp,id'; // langsung tayang -> SKP ditetapkan sekarang
        }

        $v = $request->validate($rules, [
            'narahubung_telp.regex' => 'Nomor narahubung harus 10-13 digit angka.',
            'narahubung2_telp.regex' => 'Nomor narahubung harus 10-13 digit angka.',
            'divisi.required' => 'Event panitia wajib memiliki minimal 1 sie / divisi.',
            'timelines.required' => 'Tambahkan minimal 1 tahapan timeline.',
            'syarat.required' => 'Tambahkan minimal 1 syarat pendaftaran.',
        ], [
            'Penyelenggara' => 'penyelenggara kegiatan', 'Peran' => 'peran yang dibutuhkan', 'kategori_id' => 'kategori event',
            'deskripsi' => 'deskripsi lengkap', 'judul' => 'judul event', 'narahubung_telp' => 'nomor narahubung pertama', 'narahubung_nama' => 'nama narahubung pertama', 'narahubung2_telp' => 'nomor narahubung kedua', 'narahubung2_nama' => 'nama narahubung kedua', 'tanggal_mulai' => 'tanggal mulai', 'tanggal_selesai' => 'tanggal selesai', 'deskripsi_singkat' => 'deskripsi singkat', 'kategori_pendaftar' => 'kategori pendaftar', 'timelines.*.nama' => 'nama tahapan', 'timelines.*.mulai' => 'tanggal mulai tahapan', 'timelines.*.selesai' => 'tanggal selesai tahapan', 'syarat.*.deskripsi' => 'syarat', 'divisi.*.nama' => 'nama sie', 'divisi.*.kuota' => 'kuota sie', 'divisi.*.deskripsi' => 'deskripsi sie', 'sik' => 'surat izin kegiatan (SIK)', 'skp_id' => 'kategori SKP',
        ]);

        // Sanitasi HTML dari rich text editor (cegah XSS)
        $deskripsi = Purifier::clean($v['deskripsi']);
        if (trim(strip_tags($deskripsi)) === '') {
            throw \Illuminate\Validation\ValidationException::withMessages(['deskripsi' => 'Deskripsi lengkap wajib diisi.']);
        }

        $dokumen = $isPanitia ? ($v['dokumen'] ?? []) : [];
        $eventData = [
            'organisasi_id' => $v['Penyelenggara'],
            'peran' => $v['Peran'],
            'judul' => $v['judul'],
            'nama_kegiatan' => $v['nama_kegiatan'],
            'kategori_id' => $v['kategori_id'],
            'kategori_pendaftar' => $v['kategori_pendaftar'],
            'narahubung_nama' => $v['narahubung_nama'],
            'narahubung_telp' => $v['narahubung_telp'],
            'narahubung2_nama' => $v['narahubung2_nama'] ?? null,
            'narahubung2_telp' => $v['narahubung2_telp'] ?? null,
            'tanggal_mulai' => $v['tanggal_mulai'],
            'tanggal_selesai' => $v['tanggal_selesai'],
            'lokasi' => $v['lokasi'],
            'deskripsi_singkat' => $v['deskripsi_singkat'],
            'deskripsi' => $deskripsi,
        ];
        foreach (['req_lkmm', 'req_pkkmb_fakultas', 'req_pkkmb_jurusan', 'req_pengalaman', 'req_instagram'] as $flag) {
            $eventData[$flag] = in_array($flag, $dokumen, true);
        }
        if ($user->isFmipa()) {
            $eventData['skp_id'] = $v['skp_id'];
        }

        return [
            'event' => $eventData,
            'timelines' => array_values($v['timelines']),
            'syarat' => array_values($v['syarat']),
            'divisi' => $isPanitia ? array_values($v['divisi']) : [],
        ];
    }

    /** Simpan ulang timeline, syarat, dan divisi (hapus lalu buat lagi). */
    private function syncChildren(Event $event, array $data): void
    {
        $event->timelines()->delete();
        foreach ($data['timelines'] as $i => $t) {
            $event->timelines()->create(['nama_tahapan' => $t['nama'], 'tanggal_mulai' => $t['mulai'], 'tanggal_selesai' => $t['selesai'], 'urutan' => $i]);
        }
        $event->syarat()->delete();
        foreach ($data['syarat'] as $i => $s) {
            // "butuh_berkas" hanya berlaku untuk peserta (panitia pakai dokumen wajib)
            $berkas = $event->peran === 'peserta' && ! empty($s['butuh_berkas']);
            $event->syarat()->create(['deskripsi' => $s['deskripsi'], 'butuh_berkas' => $berkas, 'urutan' => $i]);
        }
        // Divisi: hanya dihapus bila belum ada pendaftar yang memilihnya
        if ($event->peran === 'panitia') {
            $keep = [];
            foreach ($data['divisi'] as $d) {
                $fields = ['nama' => $d['nama'], 'kuota' => $d['kuota'], 'deskripsi' => $d['deskripsi']];
                $row = ! empty($d['id']) ? $event->divisi()->find($d['id']) : null;
                if ($row) {
                    $row->update($fields);
                } else {
                    $row = $event->divisi()->create($fields);
                }
                $keep[] = $row->id;
            }
            $event->divisi()->whereNotIn('id', $keep)->delete();
        } else {
            $event->divisi()->delete();
        }
    }
}
