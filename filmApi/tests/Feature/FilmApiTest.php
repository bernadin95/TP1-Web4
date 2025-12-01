<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilmApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    // Route 1
    public function test_pour_retourner_tous_les_films() 
    {
        $response = $this->getJson('/films');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'release_year', 'length', 'language_id'],
                 ]);
    }

    // Route 2
    public function test_pour_retourner_les_acteurs_pour_un_film_spécifique() 
    {
        $film = Film::first();

        $response = $this->getJson("/films/{$film->id}/actors");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'last_name', 'first_name', 'birthdate'],
                 ]);
    }

   
    public function test_pour_retourner_une_erreur_404_si_le_film_est_introuvable()
    {
        $response = $this->getJson('/films/999999/actors');

        $response->assertStatus(404);
    }

    // Route 3
    public function test_pour_retourner_un_film_avec_ses_critiques() 
    {
        $film = Film::first();

        $response = $this->getJson("/films/{$film->id}/with-critics");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'film' => ['id', 'title'],
                     'critics',
                 ]);
    }

}

