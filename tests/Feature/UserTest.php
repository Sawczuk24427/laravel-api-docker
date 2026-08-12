<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_users(){
        User::factory()->count(3)->create();
        $response = $this->getJson('/users');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_get_specific_user(){
        $user = User::factory()->create();
        $response = $this->getJson("/users/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_can_create_user(){
        $payload = [
            'name' => 'John',
            'email'=> 'john@gmail.com',
            'password' => 'john1234',
        ];

        $response = $this->postJson('/users', $payload);
        $response->assertStatus(201);
        $this -> assertDatabaseHas('users', [
            'email' => 'john@gmail.com',
            'name' => 'John'
        ]);
    }

    public function test_can_delete_user(){
        $user = User::factory()->create();
        $response = $this->deleteJson("/users/{$user->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
        'id' => $user->id
    ]);

    }

    public function test_can_edit_user(){
        $user = User::factory()->create();
        $data = [
            'name' => 'John Smith'
        ];
        $response = $this->putJson("/users/{$user->id}", $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Smith'
    ]);
    }


    public function test_cannot_create_user_without_required_fields(){
        $response = $this->postJson('/users', []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors([
            'name',
            'email',
            'password'
        ]);
        $this->assertDatabaseCount('users', 0);
    }

    public function test_cannot_create_user_with_duplicate_name(){
        User::factory()->create([
            'name'=> 'John',
        ]);
        $payload = [
            'name' => 'John',
        ];
        $response = $this->postJson('/users', $payload);
        $response ->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_cannot_create_user_with_duplicate_email(){
        User::factory()->create([
            'email'=> 'john@gmail.com',
        ]);
        $payload = [
            'email' => 'john@gmail.com',
        ];
        $response = $this->postJson('/users', $payload);
        $response ->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_cannot_update_user_with_existing_name(){
        User::factory()->create([
            'name'=> 'John',
        ]);

        $user = User::factory()->create([
            'name'=>'Paul',
        ]);

        $response = $this->putJson("/users/{$user->id}", [
            'name'=>'John'
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

    }

    public function test_user_can_keep_own_name(){
        $user = User::factory()->create([
        'name' => 'John',
    ]);

    $response = $this->put("/users/{$user->id}", [
        'name' => 'John',
    ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John',
    ]);
    }
}
