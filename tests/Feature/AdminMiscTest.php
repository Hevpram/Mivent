<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\MiventNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminMiscTest extends TestCase
{
    public function test_fmipa_creates_ormawa_account_which_can_login(): void
    {
        $this->actingAs($this->fmipa())->post(route('fmipa.ormawa.store'), ['nama' => 'X'])->assertSessionHasErrors(['nama_ketua', 'email', 'password', 'sk_ormawa'], null, 'tambah');
        $this->post(route('fmipa.ormawa.store'), [
            'nama' => 'Himpunan Mahasiswa Data Science', 'nama_ketua' => 'Ayu', 'nomor_telpon' => '081828283783',
            'email' => 'hmds@mivent.test', 'akun_sosmed' => '@hmds', 'password' => 'Ormawa@123', 'password_confirmation' => 'Ormawa@123',
            'sk_ormawa' => UploadedFile::fake()->create('sk.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('fmipa.ormawa.index'))->assertSessionHas('success');
        $u = User::where('email', 'hmds@mivent.test')->firstOrFail();
        $this->assertSame('HMDS', $u->organisasi->singkatan);
        $this->get(route('fmipa.ormawa.index'))->assertOk()->assertSee('Himpunan Mahasiswa Data Science');

        $this->put(route('fmipa.ormawa.update', $u), ['password' => 'a', 'password_confirmation' => 'b'])->assertSessionHasErrors('password', null, 'akun' . $u->id);
        $this->put(route('fmipa.ormawa.update', $u), ['password' => 'Ganti@123', 'password_confirmation' => 'Ganti@123'])->assertSessionHas('success');
        $this->assertTrue(Hash::check('Ganti@123', $u->fresh()->password));
        $this->put(route('fmipa.ormawa.update', $this->mahasiswa()), ['password' => 'Ganti@123', 'password_confirmation' => 'Ganti@123'])->assertNotFound();

        $this->post('/logout');
        $this->post('/login', ['identifier' => 'hmds@mivent.test', 'password' => 'Ganti@123'])->assertRedirect(route('dashboard'));
    }

    public function test_notifications_read_open_and_delete(): void
    {
        $m = $this->mahasiswa();
        $m->notify(new MiventNotification('Halo', 'Pesan uji', route('events.index'), 'success'));
        $m->notify(new MiventNotification('Dua', 'Pesan dua', null, 'danger'));
        $this->actingAs($m)->get(route('notifications'))->assertOk()->assertSee('Pesan uji');
        $n = $m->notifications()->where('data->judul', 'Halo')->first();
        $this->get(route('notifications.open', $n->id))->assertRedirect(route('events.index'));
        $this->assertNotNull($n->fresh()->read_at);
        $this->post(route('notifications.readAll'))->assertSessionHas('success');
        $this->assertSame(0, $m->unreadNotifications()->count());
        $this->delete(route('notifications.destroy', $n->id))->assertSessionHas('success');
        $this->assertSame(1, $m->notifications()->count());
        // notifikasi milik orang lain tidak bisa diakses
        $other = $m->notifications()->first();
        $this->actingAs($this->ormawa())->delete(route('notifications.destroy', $other->id))->assertNotFound();
        $this->get(route('notifications'))->assertOk();
    }

    public function test_account_password_change_requires_current_password(): void
    {
        $this->actingAs($this->mahasiswa())->put(route('account.password'), ['current_password' => 'salah', 'password' => 'Baru@1234', 'password_confirmation' => 'Baru@1234'])
            ->assertSessionHasErrors('current_password', null, 'password');
        $this->put(route('account.password'), ['current_password' => 'Mivent@123', 'password' => 'Baru@1234', 'password_confirmation' => 'Baru@1234'])->assertSessionHas('success');
        $this->assertTrue(Hash::check('Baru@1234', $this->mahasiswa()->password));
    }

    public function test_error_pages_render(): void
    {
        foreach ([403, 404, 419, 500, 503] as $code) {
            $html = view("errors.$code")->render();
            $this->assertStringContainsString((string) $code, $html);
        }
    }
}
