<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->unique()->randomElement([
            'Fiksi', 'Non-Fiksi', 'Sains', 'Teknologi', 'Sejarah',
            'Biografi', 'Filsafat', 'Agama', 'Kesehatan', 'Ekonomi',
        ]);

        return [
            'nama' => $nama,
            'slug' => Str::slug($nama),
        ];
    }
}
