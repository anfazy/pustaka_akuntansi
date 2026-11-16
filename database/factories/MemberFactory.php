<?php

namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nis_nip' => fake()->unique()->numerify('##########'),
            'kelas_jabatan' => fake()->randomElement(['X IPA 1', 'X IPS 2', 'XI IPA 3', 'XI IPS 1', 'XII IPA 2', 'Guru', 'Staff TU']),
            'no_hp' => fake()->phoneNumber(),
        ];
    }
}
