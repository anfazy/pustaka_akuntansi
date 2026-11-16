<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = fake()->dateTimeBetween('-3 months', 'now');
        $status = fake()->randomElement(['dipinjam', 'dikembalikan', 'terlambat']);
        $tanggalKembali = $status === 'dipinjam' ? null : fake()->dateTimeBetween($tanggalPinjam, 'now');

        return [
            'book_id' => Book::factory(),
            'member_id' => Member::factory(),
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_kembali' => $tanggalKembali,
            'status' => $status,
        ];
    }

    /**
     * Indicate the loan is currently active (not returned).
     */
    public function dipinjam(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_kembali' => null,
            'status' => 'dipinjam',
        ]);
    }

    /**
     * Indicate the loan has been returned.
     */
    public function dikembalikan(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_kembali' => fake()->dateTimeBetween($attributes['tanggal_pinjam'] ?? '-1 month', 'now'),
            'status' => 'dikembalikan',
        ]);
    }

    /**
     * Indicate the loan is overdue.
     */
    public function terlambat(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_kembali' => null,
            'status' => 'terlambat',
        ]);
    }
}
