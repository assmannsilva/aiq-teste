<?php

use App\Models\Client;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('successfully creates a client from request', function () {
    $response = $this->postJson('/api/clients', [
        'name' => 'Cauê Assmann Silva',
        'email' => 'caue@gmail.com'
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
        'client' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        ],
        'token'
    ]);
    $this->assertDatabaseHas('clients', [
        'name' => 'Cauê Assmann Silva',
        'email' => 'caue@gmail.com'
    ]);
});

it('cannot creates a client from request due to email already registered', function () {
    Client::factory()->create([
        'email' => 'caue@gmail.com'
    ]);

    $response = $this->postJson('/api/clients', [
        'name' => 'Cauê Assmann Silva',
        'email' => 'caue@gmail.com'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('cannot creates a client from request due to invalid email', function () {

    $response = $this->postJson('/api/clients', [
        'name' => 'Cauê Assmann Silva',
        'email' => 'teste'
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('cannot creates a client from request due to values not provided', function () {

    $response = $this->postJson('/api/clients');

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', "name"]);
});
