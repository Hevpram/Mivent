<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Kategori;
use App\Models\Registration;
use App\Models\Skp;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class EventFlowTest extends TestCase
{
    private function payload(User $user, string $peran, array $extra = []): array
    {
        $base = [
            'Penyelenggara' => $user->organisasi_id ?? \App\Models\Organisasi::first()->id,
            'Peran' => $peran,
            'judul' => 'Open Recruitment Uji', 'nama_kegiatan' => 'Kegiatan Uji ' . $peran,
            'kategori_id' => Kategori::first()->id, 'kategori_pendaftar' => 'mhsFMIPA',
            'narahubung_nama' => 'Andika', 'narahubung_telp' => '081234567890',
            'narahubung2_nama' => 'Ayu', 'narahubung2_telp' => '081234567891',
            'tanggal_mulai' => now()->addDays(5)->toDateString(), 'tanggal_selesai' => now()->addDays(6)->toDateString(),
            'lokasi' => 'Gedung FMIPA', 'deskripsi_singkat' => 'Deskripsi singkat', 'deskripsi' => '<p>Deskripsi <script>alert(1)</script>lengkap</p>',
            'poster' => UploadedFile::fake()->image('poster.png'),
            'sik' => UploadedFile::fake()->create('sik.pdf', 50, 'application/pdf'),
            'timelines' => [['nama' => 'Pendaftaran', 'mulai' => now()->toDateString(), 'selesai' => now()->addDay()->toDateString()]],
            'syarat' => [['deskripsi' => 'Upload KTM', 'butuh_berkas' => '1']],
        ];
        if ($peran === 'panitia') {
            $base['divisi'] = [['nama' => 'Sie Acara', 'kuota' => 5, 'deskripsi' => 'Mengatur acara'], ['nama' => 'Sie Humas', 'kuota' => 3, 'deskripsi' => 'Publikasi']];
            $base['dokumen'] = ['req_lkmm'];
        }
        if ($user->isFmipa()) {
            $base['skp_id'] = Skp::first()->id;
        }

        return array_merge($base, $extra);
    }

    public function test_langkah1_requires_penyelenggara_and_peran(): void
    {
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'peserta', ['Peran' => null, 'Penyelenggara' => null]))
            ->assertSessionHasErrors(['Peran', 'Penyelenggara']);
    }

    public function test_ormawa_cannot_submit_for_other_organisasi(): void
    {
        $lain = $this->ormawaLain()->organisasi_id;
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'peserta', ['Penyelenggara' => $lain]))
            ->assertSessionHasErrors('Penyelenggara');
    }

    public function test_panitia_event_requires_sie(): void
    {
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'panitia', ['divisi' => []]))
            ->assertSessionHasErrors('divisi');
    }

    public function test_peserta_event_ignores_panitia_modules_server_side(): void
    {
        $data = $this->payload($this->ormawa(), 'peserta', ['divisi' => [['nama' => 'X', 'kuota' => 1, 'deskripsi' => 'Y']], 'dokumen' => ['req_lkmm']]);
        $this->actingAs($this->ormawa())->post(route('manage.store'), $data)->assertRedirect(route('manage.index'));
        $e = Event::where('nama_kegiatan', 'Kegiatan Uji peserta')->firstOrFail();
        $this->assertSame(0, $e->divisi()->count());
        $this->assertFalse((bool) $e->req_lkmm);
        $this->assertTrue((bool) $e->syarat()->first()->butuh_berkas);
        $this->assertStringNotContainsString('<script', $e->deskripsi);
    }

    public function test_ormawa_event_waits_for_verification_and_fmipa_event_publishes_directly(): void
    {
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'panitia'))->assertRedirect(route('manage.index'));
        $ormawaEvent = Event::where('nama_kegiatan', 'Kegiatan Uji panitia')->firstOrFail();
        $this->assertSame('menunggu', $ormawaEvent->status);
        $this->assertSame(2, $ormawaEvent->divisi()->count());
        $this->assertTrue($this->fmipa()->notifications()->exists());
        $this->get(route("events.show", $ormawaEvent))->assertOk(); // pengelola boleh pratinjau
        $this->actingAs($this->mahasiswa())->get(route("events.show", $ormawaEvent))->assertNotFound();

        $this->actingAs($this->fmipa())->post(route('manage.store'), $this->payload($this->fmipa(), 'peserta', ['nama_kegiatan' => 'Event FMIPA', 'sik' => null]))
            ->assertRedirect(route('manage.index'));
        $fm = Event::where('nama_kegiatan', 'Event FMIPA')->firstOrFail();
        $this->assertSame('disetujui', $fm->status);
        $this->get(route('manage.show', $fm))->assertOk();
        $this->post('/logout');
        $this->get(route('events.show', $fm))->assertOk();
    }

    public function test_verification_revise_edit_and_approve(): void
    {
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'peserta'));
        $e = Event::where('nama_kegiatan', 'Kegiatan Uji peserta')->firstOrFail();

        $this->actingAs($this->fmipa())->post(route('fmipa.verifikasi.revise', $e), ['catatan_revisi' => ''])->assertSessionHasErrors('catatan_revisi');
        $this->post(route('fmipa.verifikasi.revise', $e), ['catatan_revisi' => 'Perbaiki poster'])->assertRedirect();
        $this->assertSame('revisi', $e->fresh()->status);

        $this->actingAs($this->ormawa())->get(route('manage.edit', $e))->assertOk()->assertSee('Perbaiki poster');
        $this->put(route('manage.update', $e), $this->payload($this->ormawa(), 'peserta', ['poster' => null, 'sik' => null, 'judul' => 'Judul Revisi']))->assertRedirect();
        $this->assertSame('menunggu', $e->fresh()->status);
        $this->assertSame('Judul Revisi', $e->fresh()->judul);

        $this->actingAs($this->fmipa())->post(route('fmipa.verifikasi.approve', $e), ['skp_id' => Skp::first()->id])->assertRedirect();
        $this->assertSame('disetujui', $e->fresh()->status);
    }

    /** Alur lengkap event panitia: daftar -> seleksi berkas -> wawancara -> selesai -> sertifikat. */
    public function test_full_panitia_flow(): void
    {
        $this->actingAs($this->ormawa())->post(route('manage.store'), $this->payload($this->ormawa(), 'panitia', ['dokumen' => ['req_lkmm', 'req_instagram']]));
        $e = Event::where('nama_kegiatan', 'Kegiatan Uji panitia')->firstOrFail();
        $this->actingAs($this->fmipa())->post(route('fmipa.verifikasi.approve', $e), ['skp_id' => Skp::first()->id]);
        [$sie1, $sie2] = $e->divisi()->orderBy('id')->get()->all();

        // Mahasiswa 1: data tidak lengkap ditolak
        $mhs = $this->mahasiswa();
        $mhs->update(['sertifikat_lkmm' => null]);
        $this->actingAs($mhs)->get(route('registrations.create', $e))->assertOk()->assertSee('Sie Acara');
        $this->post(route('registrations.store', $e), ['phone' => '081234567890', 'divisi_id' => $sie1->id, 'divisi2_id' => $sie1->id, 'alasan' => 'Ingin belajar'])
            ->assertSessionHasErrors(['divisi2_id', 'setuju', 'berkas.sertifikat_lkmm', 'berkas.instagram']);

        $ok = fn ($divisi) => [
            'phone' => '081234567890', 'divisi_id' => $divisi, 'divisi2_id' => $sie2->id, 'alasan' => 'Ingin belajar', 'setuju' => '1',
            'berkas' => ['sertifikat_lkmm' => UploadedFile::fake()->create('lkmm.pdf', 10, 'application/pdf'), 'instagram' => UploadedFile::fake()->image('ig.jpg')],
        ];
        $this->post(route('registrations.store', $e), $ok($sie1->id))->assertRedirect()->assertSessionHas('registered');
        $r1 = Registration::where('event_id', $e->id)->where('user_id', $mhs->id)->firstOrFail();
        $this->assertSame(2, $r1->files()->count());
        // daftar dua kali tidak bisa
        $this->post(route('registrations.store', $e), $ok($sie1->id))->assertRedirect();
        $this->assertSame(1, Registration::where('event_id', $e->id)->where('user_id', $mhs->id)->count());
        $this->get(route('registrations.show', $r1))->assertOk();
        $this->get(route('registrations.pdf', $r1))->assertOk();
        $this->get(route('events.show', $e))->assertOk();

        $mhs2 = $this->mahasiswa2();
        $this->actingAs($mhs2)->post(route('registrations.store', $e), $ok($sie2->id === $sie1->id ? $sie1->id : $sie1->id))->assertRedirect();
        $r2 = Registration::where('event_id', $e->id)->where('user_id', $mhs2->id)->firstOrFail();
        // mahasiswa lain tidak boleh melihat pendaftaran orang lain
        $this->get(route('registrations.show', $r1))->assertForbidden();

        // Pengelola: halaman kelola & berkas
        $this->actingAs($this->ormawa());
        $this->get(route('manage.show', $e))->assertOk()->assertSee($mhs->name);
        $this->get(route('manage.registrant', [$e, $r1]))->assertOk();
        $this->get(route('manage.export', $e))->assertOk();
        $this->get(route('manage.zip', [$e, $r1]))->assertOk();
        $this->get(route('manage.bukti', [$e, $r1]))->assertOk();

        // seleksi belum boleh saat pendaftaran
        $this->post(route('manage.seleksi', $e), ['aksi' => 'terima_semua'])->assertSessionHas('error');

        // Tahap -> seleksi berkas (pendaftaran ditutup)
        $this->post(route('manage.tahap', $e))->assertSessionHas('success');
        $this->assertSame('seleksi_berkas', $e->fresh()->tahap);
        $this->get(route('manage.show', $e))->assertOk()->assertSee('Seleksi Berkas');
        $this->actingAs($this->mahasiswa())->get(route('registrations.create', $e))->assertRedirect();
        $this->actingAs($this->ormawa());

        // r1 berkas salah (dengan catatan), r2 sesuai
        $f = $r1->files()->first();
        $this->post(route('manage.keputusan', [$e, $r1]), ['keputusan' => 'tolak', 'salah' => [$f->id], 'catatan' => [$f->id => 'Tanda tangan kurang']])->assertSessionHas('success');
        $this->assertTrue($f->fresh()->salah);
        $this->get(route('manage.registrant', [$e, $r1]))->assertOk()->assertSee('Tanda tangan kurang');

        // mahasiswa memperbaiki berkas
        $this->actingAs($this->mahasiswa())->post(route('registrations.berkas', $r1), ['berkas' => [$f->id => UploadedFile::fake()->create('baru.pdf', 10, 'application/pdf')]])->assertRedirect();
        $this->assertFalse((bool) $f->fresh()->salah);

        $this->actingAs($this->ormawa());
        $this->post(route('manage.keputusan', [$e, $r1]), ['keputusan' => 'terima']);
        $this->post(route('manage.keputusan', [$e, $r2]), ['keputusan' => 'tolak']);
        // tidak bisa lanjut tahap sebelum disimpan
        $this->post(route('manage.tahap', $e))->assertSessionHas('error');
        $this->post(route('manage.seleksi', $e), ['aksi' => 'simpan'])->assertSessionHas('success');
        $this->assertSame('lolos_berkas', $r1->fresh()->status);
        $this->assertSame('ditolak', $r2->fresh()->status);

        // Wawancara
        $this->post(route('manage.tahap', $e))->assertSessionHas('success');
        $this->assertSame('seleksi_wawancara', $e->fresh()->tahap);
        $this->get(route('manage.show', $e))->assertOk()->assertSee('Wawancara');
        $this->post(route('manage.keputusan', [$e, $r2]), ['keputusan' => 'terima'])->assertSessionHas('error');
        $this->post(route('manage.seleksi', $e), ['aksi' => 'terima_semua']);
        $this->post(route('manage.seleksi', $e), ['aksi' => 'reset']);
        $this->assertNull($r1->fresh()->keputusan);
        $this->post(route('manage.keputusan', [$e, $r1]), ['keputusan' => 'terima']);
        $this->post(route('manage.seleksi', $e), ['aksi' => 'simpan']);
        $this->assertSame('diterima', $r1->fresh()->status);
        $this->assertSame($sie1->id, $r1->fresh()->divisi_diterima_id);

        // Selesai + sertifikat
        $this->post(route('manage.sertifikat.store', $e), ['file' => UploadedFile::fake()->create('s.pdf', 10, 'application/pdf')])->assertStatus(422);
        $this->post(route('manage.tahap', $e))->assertSessionHas('success');
        $this->assertSame('selesai', $e->fresh()->tahap);
        $this->get(route('manage.show', $e))->assertOk()->assertSee('Belum diunggah');
        $this->get(route('manage.template', $e))->assertOk();
        $this->post(route('manage.sertifikat.store', $e), ['registration_id' => $r2->id, 'file' => UploadedFile::fake()->create('s.pdf', 10, 'application/pdf')])->assertSessionHasErrors('registration_id');
        $this->post(route('manage.sertifikat.store', $e), ['file' => UploadedFile::fake()->create('s.pdf', 10, 'application/pdf')])->assertSessionHas('success');
        $this->get(route('manage.show', $e))->assertOk()->assertSee('Lihat Sertifikat');

        $this->actingAs($this->mahasiswa())->get(route('registrations.show', $r1))->assertOk()->assertSee('Acara Selesai');
        $this->get(route('registrations.index'))->assertOk();
        $this->get(route('notifications'))->assertOk()->assertSee('Sertifikat tersedia');

        $this->actingAs($this->ormawa());
        $sid = $e->sertifikat()->first()->id;
        $this->delete(route('manage.sertifikat.destroy', [$e, $sid]))->assertSessionHas('success');
        $this->assertSame(0, $e->sertifikat()->count());
    }

    public function test_peserta_flow_requires_syarat_berkas_and_finishes_without_wawancara(): void
    {
        $this->actingAs($this->fmipa())->post(route('manage.store'), $this->payload($this->fmipa(), 'peserta', ['nama_kegiatan' => 'Seminar Uji', 'sik' => null]));
        $e = Event::where('nama_kegiatan', 'Seminar Uji')->firstOrFail();
        $syarat = $e->syarat()->first();

        $this->actingAs($this->mahasiswa())->get(route('registrations.create', $e))->assertOk()->assertDontSee('Pilih Sie');
        $this->post(route('registrations.store', $e), ['phone' => '081234567890', 'setuju' => '1'])->assertSessionHasErrors("syarat.{$syarat->id}");
        $this->post(route('registrations.store', $e), ['phone' => '081234567890', 'setuju' => '1', 'syarat' => [$syarat->id => UploadedFile::fake()->image('ktm.jpg')]])->assertRedirect();
        $r = Registration::where('event_id', $e->id)->firstOrFail();
        $this->assertNull($r->divisi_id);

        $this->actingAs($this->fmipa());
        $this->post(route('manage.tahap', $e));
        $this->post(route('manage.seleksi', $e), ['aksi' => 'terima_semua']);
        $this->post(route('manage.seleksi', $e), ['aksi' => 'simpan']);
        $this->assertSame('diterima', $r->fresh()->status);
        $this->post(route('manage.tahap', $e));
        $this->assertSame('selesai', $e->fresh()->tahap);
        $this->get(route('manage.show', $e))->assertOk();
    }
}
