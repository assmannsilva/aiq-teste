<?php

use App\Models\Client;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it("updates successfully a client authenticated email", function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $response = $this->patchJson("api/clients/me", [
        "email" => "novoemail@gmail.com",
    ]);

    $response->assertStatus(200);
    $client->refresh();
    $this->assertEquals("novoemail@gmail.com", $client->email);
});

it("updates successfully a client authenticated name", function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $response = $this->patchJson("api/clients/me", [
        "name" => "Teste"
    ]);

    $response->assertStatus(200);
    $client->refresh();
    $this->assertEquals("Teste", $client->name);
});

it("updates successfully a client authenticated both name and email", function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $response = $this->patchJson("api/clients/me", [
        "name" => "Teste",
        "email" => "novoemail@gmail.com"
    ]);

    $response->assertStatus(200);
    $client->refresh();
    $this->assertEquals("Teste", $client->name);
    $this->assertEquals("novoemail@gmail.com", $client->email);
});

it("cannot updates  a client due to email already exists", function () {
    $clientExisting = Client::factory()->create([
        "email" => "caue@gmail.com"
    ]);

    /** @var \App\Models\Client $client */
    $client = Client::factory()->create(["email" => "outroemail@gmail.com"]);
    actingAs($client, "sanctum");

    $response = $this->patchJson("api/clients/me", [
        "email" => "caue@gmail.com"
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);

    $client->refresh();
    $this->assertEquals("outroemail@gmail.com", $client->email);
});

it("cannot updates a client due to none data provided", function () {

    /** @var \App\Models\Client $client */
    $client = Client::factory()->create(["email" => "outroemail@gmail.com"]);
    actingAs($client, "sanctum");

    $response = $this->patchJson("api/clients/me");

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['data']);

    $client->refresh();
    $this->assertEquals("outroemail@gmail.com", $client->email);
});

it("cannot updates a client due to not be authenticated", function () {

    $response = $this->patchJson("api/clients/me", ["email" => "outroemail@gmail.com"]);
    $response->assertStatus(401);
});
