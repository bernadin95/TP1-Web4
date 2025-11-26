<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Critic;
use App\Models\Film;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Critic>
 */
class CriticFactory extends Factory
{
    protected $model = Critic::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filmId = Film::inRandomOrder()->value('id');

        return [
            'film_id'   => $filmId,
            'score'     => fake()->randomFloat(1, 1, 10),
            'comment'   => fake()->paragraph(),
            'created_at'=> now(),
            'updated_at'=> now(),
        ];
    }
}
