<?php

use App\Dtos\FakeStoreProductDto;
use App\Exceptions\ExternalApiException;
use App\External\FakeStoreClient;
use App\Models\Client;
use App\Models\Product;
use App\Repositories\FavoriteRepository;
use App\Repositories\ProductsRepository;
use App\Services\FavoriteService;
use App\Services\ProductsService;
use Illuminate\Support\Facades\Cache;

use function Pest\Laravel\actingAs;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

// Resolvi deixar apenas um teste para sucesso, quando busca na API e outro pra falha total
// No teste GetCachedProductTest os demais casos são cobertos
// Aqui eu queria testar mais se os dados do banco de dados estão sendo atualizados corretamente durante a execução da função
// E se o relacionamento entre cliente e favorito está sendo criado corretamente

it('successfully adds a new favorite product to client from the API', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");
    $fakestoreId = "999";

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.$fakestoreId", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $closure());

    $fakeStoreMock = Mockery::mock(FakeStoreClient::class, function ($mock) use ($fakestoreId) {
        $mock->shouldReceive('getProductById')
            ->with($fakestoreId)
            ->andReturn(new FakeStoreProductDto(
                id: $fakestoreId,
                title: 'Teste',
                price: 10.45,
                image: 'https://www.google.com/',
                rate: 4.5,
                ratingCount: 120,
            ));
    });
    $favoriteService = new FavoriteService(
        new ProductsService(
            new ProductsRepository,
            $fakeStoreMock
        ),
        new FavoriteRepository,
    );

    $favoriteService->addFavoriteIntoClient($client, $fakestoreId);

    $this->assertDatabaseHas('favorites', [
        'client_id' => $client->id,
        'fake_store_product_id' => $fakestoreId,
    ]);

    $this->assertDatabaseHas('products', [
        'fakestore_product_id' => $fakestoreId,
        'title' => 'Teste',
        'price_in_cents' => 1045,
        'image_url' => 'https://www.google.com/',
    ]);
});

it('cannot adds a new favorite product due to external api error and no data in database', function () {
    /** @var \App\Models\Client $client */
    $client = Client::factory()->create();
    actingAs($client, "sanctum");
    $fakestoreId = "invalid-id";

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.$fakestoreId", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $closure());

    $fakeStoreMock = Mockery::mock(FakeStoreClient::class, function ($mock) {
        $mock->shouldReceive('getProductById')->andThrow(ExternalApiException::class);
    });

    $favoriteService = new FavoriteService(
        new ProductsService(
            new ProductsRepository,
            $fakeStoreMock
        ),
        new FavoriteRepository,
    );

    expect(fn() => $favoriteService->addFavoriteIntoClient($client, $fakestoreId))->toThrow(ExternalApiException::class);

    $this->assertDatabaseMissing('favorites', [
        'client_id' => $client->id,
        'fake_store_product_id' => $fakestoreId,
    ]);
});
