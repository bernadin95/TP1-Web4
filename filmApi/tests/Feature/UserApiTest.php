<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    // Route 4
    public function test_pour_creer_un_utilisateur_avec_succes() 
    {
        $payload = [
            'login'      => 'newuser',
            'password'   => 'secret123',
            'email'      => 'newuser@example.com',
            'last_name'  => 'Doe',
            'first_name' => 'John',
        ];

        $response = $this->postJson('/users', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'login' => 'newuser',
                     'email' => 'newuser@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'login' => 'newuser',
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_pour_retourner_une_erreur_lorsque_des_champs_ne_sont_pas_remplis()
    {
        $payload = [
            'email'      => 'invalid@example.com',
            'last_name'  => 'Doe',
            'first_name' => 'John',
        ];

        $response = $this->postJson('/users', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login', 'password']);
    }

    // Route 5
    public function test_pour_mettre_a_jour_un_utilisateur_avec_succes() 
    {
        $user = User::factory()->create();

        $payload = [
            'login'      => 'updatedlogin',
            'password'   => 'newpassword',
            'email'      => 'updated@example.com',
            'last_name'  => 'UpdatedLast',
            'first_name' => 'UpdatedFirst',
        ];

        $response = $this->putJson("/users/{$user->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'login' => 'updatedlogin',
                     'email' => 'updated@example.com',
                 ]);

        $this->assertDatabaseHas('users', [
            'id'    => $user->id,
            'login' => 'updatedlogin',
        ]);
    }

    public function test_pour_retourner_une_erreur_404_lors_de_la_mise_a_jour_d_un_utilisateur_inexistant()
    {
        $payload = [
            'login'      => 'fake',
            'password'   => 'secret123',
            'email'      => 'fake@example.com',
            'last_name'  => 'Fake',
            'first_name' => 'User',
        ];

        $response = $this->putJson('/users/999999', $payload);

        $response->assertStatus(404);
    }
}
