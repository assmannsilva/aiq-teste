<?php

use App\Models\Client;
use App\Models\Favorite;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('sucessfully deletes a favorite', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");
    Favorite::factory()->create([
        "client_id" => $client->id,
        "fake_store_product_id" => 1
    ]);

    $response = $this->delete('api/favorites/1');
    $response->assertStatus(204);
});

it('canot deletes a favorite due to not exists', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $client2 = Client::factory()->create();
    Favorite::factory()->create([
        "client_id" => $client2->id,
        "fake_store_product_id" => 1
    ]);


    $response = $this->delete('api/favorites/1');
    $response->assertStatus(404);
});
