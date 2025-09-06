<?php

use App\Dtos\FakeStoreProductDto;
use App\Exceptions\ExternalApiException;
use App\Models\Client;
use App\Services\FavoriteService;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('aceepts a valid favorite product request with rating', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, 'sanctum');

    $favoriteServiceMock = Mockery::mock(FavoriteService::class);
    $favoriteServiceMock
        ->shouldReceive('addFavoriteIntoClient')
        ->once()
        ->with($client, 999)
        ->andReturn(new FakeStoreProductDto(
            id: 999,
            title: 'Teste',
            price: 10.45,
            image: 'https://www.google.com/',
            rate: 4.5,
            ratingCount: 120,
        ));

    $this->app->instance(FavoriteService::class, $favoriteServiceMock);

    $response = $this->postJson('/api/favorites', [
        'product_id' => 999,
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'id' => 999,
        'title' => 'Teste',
        'price' => 10.45,
        'image' => 'https://www.google.com/',
        'rate' => 4.5,
        'ratingCount' => 120,
    ]);
});

it('aceepts a valid favorite product request without rating', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, 'sanctum');

    $favoriteServiceMock = Mockery::mock(FavoriteService::class);
    $favoriteServiceMock
        ->shouldReceive('addFavoriteIntoClient')
        ->once()
        ->with($client, 999)
        ->andReturn(new FakeStoreProductDto(
            id: 999,
            title: 'Teste',
            price: 10.45,
            image: 'https://www.google.com/',
        ));

    $this->app->instance(FavoriteService::class, $favoriteServiceMock);

    $response = $this->postJson('/api/favorites', [
        'product_id' => 999,
    ]);

    $response->assertStatus(201);
    $response->assertJson([
        'id' => 999,
        'title' => 'Teste',
        'price' => 10.45,
        'image' => 'https://www.google.com/',
    ]);
});

it('doesnt aceepts a favorite product because doenst exists', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, 'sanctum');

    $favoriteServiceMock = Mockery::mock(FavoriteService::class);
    $favoriteServiceMock
        ->shouldReceive('addFavoriteIntoClient')
        ->once()
        ->with($client, 999)
        ->andThrow(new ExternalApiException("Invalid Product Informed", 400, null));

    $this->app->instance(FavoriteService::class, $favoriteServiceMock);

    $response = $this->postJson('/api/favorites', [
        'product_id' => 999,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
        'error' => 'Invalid Product Informed',
    ]);
});

it('doesnt aceepts a favorite product because the product_id is non numeric', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, 'sanctum');


    $response = $this->postJson('/api/favorites', [
        'product_id' => "non_numeric",
    ]);

    $response->assertStatus(422);
});
