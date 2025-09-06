<?php

use App\Models\Client;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('shows client current authenticated', function () {

    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $response = $this->getJson("api/clients/me");
    $response->assertStatus(200);
    $response->assertJson([
        "client" => [
            "id" => $client->id,
            "name" => $client->name,
            "email" => $client->email,
        ]
    ]);
});
