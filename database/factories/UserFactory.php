<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** Factory user untuk testing. Default: mahasiswa terverifikasi. */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => User::MAHASISWA,
            'nim' => (string) fake()->unique()->numerify('25085#####'),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '0812' . fake()->numerify('#######'),
            'angkatan' => 2025,
            'email_verified_at' => now(),
            'password' => 'password',
            'remember_token' => Str::random(10),
        ];
    }

    public function role(string $role): static
    {
        return $this->state(fn () => ['role' => $role, 'nim' => null, 'username' => fake()->unique()->userName()]);
    }
}
