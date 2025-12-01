<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Critic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CriticApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    // Route 6
    public function test_pour_supprimer_une_critique_avec_succes() 
    {
        $critic = Critic::first(); 

        $response = $this->deleteJson("/critics/{$critic->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('critics', [
            'id' => $critic->id,
        ]);
    }

    public function test_pour_retourner_une_erreur_404_lors_de_la_suppression_d_une_critique_inexistante()
    {
        $response = $this->deleteJson('/critics/999999');

        $response->assertStatus(404);
    }
}
