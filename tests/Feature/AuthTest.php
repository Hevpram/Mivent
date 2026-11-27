<?php

namespace Tests\Feature;

use App\Mail\KodeMail;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login_page_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('identifier', false);
    }

    public function test_each_role_can_login_with_nim_username_or_email(): void
    {
        $this->post('/login', ['identifier' => '2508561093', 'password' => 'Mivent@123'])->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($this->mahasiswa());
        $this->post('/logout');

        $this->post('/login', ['identifier' => 'bemfmipa', 'password' => 'Mivent@123'])->assertRedirect(route('dashboard'));
        $this->post('/logout');

        $this->post('/login', ['identifier' => 'fmipa@mivent.test', 'password' => 'Mivent@123'])->assertRedirect(route('dashboard'));
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->from('/login')->post('/login', ['identifier' => '2508561093', 'password' => 'salah'])
            ->assertRedirect('/login')->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_register_then_verify_email_then_login(): void
    {
        Mail::fake();
        $kode = null;
        $this->post('/registrasi', [
            'name' => 'Mahasiswa Baru', 'nim' => '2508561999', 'jurusan_id' => Jurusan::first()->id,
            'angkatan' => 2025, 'phone' => '081234567890', 'email' => 'baru@student.unud.ac.id',
            'password' => 'Rahasia@123', 'password_confirmation' => 'Rahasia@123', 'setuju' => '1',
        ])->assertRedirect(route('verification.show'));

        Mail::assertSent(KodeMail::class, function (KodeMail $m) use (&$kode) { $kode = $m->kode; return true; });
        $this->get(route('verification.show'))->assertOk();
        $this->post(route('verification.verify'), ['kode' => '000000'])->assertSessionHasErrors('kode');
        $this->post(route('verification.verify'), ['kode' => $kode])->assertRedirect(route('login'));
        $this->assertNotNull(User::where('nim', '2508561999')->first()->email_verified_at);

        $this->post('/login', ['identifier' => '2508561999', 'password' => 'Rahasia@123'])->assertRedirect(route('home'));
    }

    public function test_register_validates_password_rules_and_phone(): void
    {
        $this->post('/registrasi', [
            'name' => 'X', 'nim' => '2508561998', 'jurusan_id' => Jurusan::first()->id, 'angkatan' => 2025,
            'phone' => '12ab', 'email' => 'x@y.id', 'password' => 'lemah', 'password_confirmation' => 'lemah',
        ])->assertSessionHasErrors(['phone', 'password', 'setuju']);
    }

    public function test_forgot_password_full_flow(): void
    {
        Mail::fake();
        $kode = null;
        $this->post(route('password.email'), ['akun' => 'mahasiswa@mivent.test'])->assertRedirect(route('password.code'));
        Mail::assertSent(KodeMail::class, function (KodeMail $m) use (&$kode) { $kode = $m->kode; return true; });
        $this->get(route('password.code'))->assertOk();
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
        $this->post(route('password.verify'), ['kode' => $kode])->assertRedirect(route('password.reset'));
        $this->get(route('password.reset'))->assertOk();
        $this->post(route('password.update'), ['password' => 'Baru@1234', 'password_confirmation' => 'Baru@1234'])->assertRedirect(route('login'));
        $this->post('/login', ['identifier' => '2508561093', 'password' => 'Baru@1234'])->assertRedirect(route('home'));
    }

    public function test_mail_failure_does_not_crash_registration(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));
        $this->post(route('password.email'), ['akun' => 'mahasiswa@mivent.test'])
            ->assertRedirect(route('password.code'))->assertSessionHas('error');
    }
}
