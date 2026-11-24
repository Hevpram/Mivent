<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /** Jalankan DatabaseSeeder (data contoh + akun demo) sebelum tiap test. */
    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Storage::fake('public');
    }

    protected function mahasiswa(): User { return User::where('email', 'mahasiswa@mivent.test')->firstOrFail(); }
    protected function mahasiswa2(): User { return User::where('email', 'ayu@mivent.test')->firstOrFail(); }
    protected function ormawa(): User { return User::where('email', 'bem@mivent.test')->firstOrFail(); }
    protected function ormawaLain(): User { return User::where('email', 'sic@mivent.test')->firstOrFail(); }
    protected function fmipa(): User { return User::where('email', 'fmipa@mivent.test')->firstOrFail(); }
}
