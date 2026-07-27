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
        $response = $this->get('/users');
        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_get_specific_user(){
        $user = User::factory()->create();
        $response = $this->get("/users/{$user->id}");
        $response->assertStatus(200);
    }

    public function test_can_create_user(){
        $payload = [
            'name' => 'Adam',
            'email'=> 'adam@gmail.com',
            'password' => 'adam1234',
        ];

        $response = $this->post('/users', $payload);
        $response->assertStatus(201);
        $this -> assertDatabaseHas('users', [
            'email' => 'adam@gmail.com',
            'name' => 'Adam'
        ]);
    }

    public function test_can_delete_user(){
        $user = User::factory()->create();
        $response = $this->delete("/users/{$user->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', [
        'id' => $user->id
    ]);

    }

    public function test_can_edit_user(){
        $user = User::factory()->create();
        $data = [
            'name' => 'Adam Kowalski'
        ];
        $response = $this->put("/users/{$user->id}", $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Adam Kowalski'
    ]);
    }

}
