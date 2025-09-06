<?php

use App\Dtos\FakeStoreProductDto;
use App\Models\Client;
use App\Models\Favorite;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('successfully retrieves a favorite product', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");

    $fakeStoreProductDto = new FakeStoreProductDto(
        id: 1,
        title: 'Teste',
        price: 10.99,
        image: 'https://google.com/',
        rate: 4.5,
        ratingCount: 120,
    );

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.1", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $fakeStoreProductDto);

    $favorite = Favorite::factory()->create([
        "client_id" => $client->id,
        "product_id" => 1
    ]);

    $response = $this->get('api/favorites/1');
    $response->assertStatus(200);
});

it('doesnt retrieves a favorite product', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");


    $response = $this->get('api/favorites/1');
    $response->assertStatus(404);
});
