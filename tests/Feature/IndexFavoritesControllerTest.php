<?php

use App\Models\Client;
use App\Models\Favorite;
use App\Services\ProductsService;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
beforeEach(function () {
    $productService = Mockery::mock(ProductsService::class);
    $productService->shouldReceive('getCachedProductByFakeStoreId')
        ->andReturnUsing(function ($fakestoreId) {
            return new \App\Dtos\FakeStoreProductDto(
                id: $fakestoreId,
                title: "Produto $fakestoreId",
                price: 10.00,
                image: "https://google.com",
                rate: 4.0,
                ratingCount: 100,
            );
        });

    $this->app->instance(ProductsService::class, $productService);
});


it('returns a list of 2 favorite products paginated', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    $favorites = Favorite::factory()->count(5)->create([
        'client_id' => $client->id,
    ]);

    actingAs($client, 'sanctum');

    $response = $this->getJson('/api/favorites?per_page=2');

    $response->assertStatus(200);
    $response->assertJsonCount(2, 'data');
});

it('returns a list of all possible favorite products paginated', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    $favorites = Favorite::factory()->count(5)->create([
        'client_id' => $client->id,
    ]);

    $client2 = Client::factory()->create();
    $favorites = Favorite::factory()->count(5)->create([
        'client_id' => $client2->id,
    ]);

    actingAs($client, 'sanctum');

    $response = $this->getJson('/api/favorites?per_page=15');

    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
});

it('cannot search with a per_page greater than 100', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    $favorites = Favorite::factory()->count(100)->create([
        'client_id' => $client->id,
    ]);

    actingAs($client, 'sanctum');

    $response = $this->getJson('/api/favorites?per_page=100000');

    $response->assertStatus(422);
});
