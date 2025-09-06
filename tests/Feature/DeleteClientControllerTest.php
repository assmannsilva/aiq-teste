<?php

use App\Models\Client;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('deletes client current authenticated', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $response = $this->deleteJson("api/clients/me");
    $response->assertStatus(204);
});

it('cannot deletes client due to be not authenticated', function () {
    $response = $this->deleteJson("api/clients/me");
    $response->assertStatus(401);
});
