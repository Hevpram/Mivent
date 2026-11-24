<?php

namespace Tests\Feature;

use App\Models\Event;
use Tests\TestCase;

class AccessTest extends TestCase
{
    public function test_public_pages_render_for_guest(): void
    {
        $event = Event::published()->first();
        foreach (['/', '/event', '/faq', '/login', '/registrasi', '/lupa-password', route('events.show', $event)] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/tidak-ada')->assertNotFound()->assertSee('Halaman Tidak Ditemukan');
    }

    public function test_unpublished_event_is_hidden_from_public(): void
    {
        $menunggu = Event::where('status', 'menunggu')->first();
        $this->get(route('events.show', $menunggu))->assertNotFound();
        $this->actingAs($this->mahasiswa())->get(route('events.show', $menunggu))->assertNotFound();
    }

    public function test_guest_is_redirected_from_private_pages(): void
    {
        foreach (['/akun', '/dashboard', '/event-diikuti', '/notifikasi', '/kelola-event', '/fmipa/verifikasi'] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_role_middleware(): void
    {
        $this->actingAs($this->mahasiswa());
        foreach (['/dashboard', '/kelola-event', '/kelola-event/ajukan', '/fmipa/verifikasi', '/fmipa/akun-ormawa'] as $url) {
            $this->get($url)->assertForbidden()->assertSee('Akses Ditolak');
        }
        $this->actingAs($this->ormawa());
        $this->get('/fmipa/verifikasi')->assertForbidden();
        $this->get('/fmipa/akun-ormawa')->assertForbidden();
        $this->get('/event-diikuti')->assertForbidden();
    }

    public function test_ormawa_cannot_manage_other_ormawa_event(): void
    {
        $milikLain = Event::where('user_id', '!=', $this->ormawa()->id)->where('organisasi_id', '!=', $this->ormawa()->organisasi_id)->first();
        $this->actingAs($this->ormawa())->get(route('manage.show', $milikLain))->assertForbidden();
    }

    public function test_every_page_renders_per_role(): void
    {
        $this->actingAs($this->mahasiswa());
        foreach (['/', '/event', '/event-diikuti', '/akun', '/notifikasi', '/faq'] as $url) {
            $this->get($url)->assertOk();
        }

        $this->actingAs($this->ormawa());
        foreach (['/dashboard', '/jelajahi-event', '/kelola-event', '/kelola-event/ajukan', '/akun', '/notifikasi', '/faq'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (Event::managedBy($this->ormawa())->get() as $e) {
            $this->get(route('manage.show', $e))->assertOk();
            $this->get(route('events.show', $e))->assertOk();
        }

        $this->actingAs($this->fmipa());
        foreach (['/dashboard', '/jelajahi-event', '/kelola-event', '/kelola-event/ajukan', '/fmipa/verifikasi', '/fmipa/akun-ormawa', '/akun', '/notifikasi'] as $url) {
            $this->get($url)->assertOk();
        }
        foreach (Event::whereIn('status', ['menunggu', 'revisi', 'disetujui'])->get() as $e) {
            $this->get(route('fmipa.verifikasi.show', $e))->assertOk();
        }
    }
}
