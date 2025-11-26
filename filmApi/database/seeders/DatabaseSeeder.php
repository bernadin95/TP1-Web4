<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Critic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            LanguagesSeeder::class,
            FilmsSeeder::class,
            ActorsSeeder::class,
            ActorFilmSeeder::class,
        ]);

        User::factory(10)->has(Critic::factory(30))->create();
    }
}
