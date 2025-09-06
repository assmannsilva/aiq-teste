<?php

use App\Dtos\FakeStoreProductDto;
use App\Exceptions\ExternalApiException;
use App\External\FakeStoreClient;
use App\Models\Product;
use App\Repositories\ProductsRepository;
use App\Services\ProductsService;
use Illuminate\Support\Facades\Cache;

it('sucessfully gets the product from Client and updates the database', function () {
    $fakestoreProductId = '1';
    $fakeStoreProductDto = new FakeStoreProductDto(
        id: $fakestoreProductId,
        title: 'Teste',
        price: 10.99,
        image: 'https://google.com/',
        rate: 4.5,
        ratingCount: 120,
    );

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.$fakestoreProductId", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $closure());

    $fakeStoreClientMock = Mockery::mock(FakeStoreClient::class);

    $fakeStoreClientMock->shouldReceive('getProductById')
        ->once()
        ->with($fakestoreProductId)
        ->andReturn($fakeStoreProductDto);

    $productsRepositoryMock = Mockery::mock(ProductsRepository::class);
    $productsRepositoryMock->shouldReceive('updateOrCreateByFakeStoreId')
        ->once()
        ->with(
            (string) $fakeStoreProductDto->id,
            [
                'title' => $fakeStoreProductDto->title,
                'price_in_cents' => 1099,
                'image_url' => $fakeStoreProductDto->image
            ]
        );

    $productsService = new ProductsService($productsRepositoryMock, $fakeStoreClientMock);
    $result = $productsService->getCachedProductByFakeStoreId($fakestoreProductId);

    expect($result)->toBeInstanceOf(FakeStoreProductDto::class)
        ->and($result->id)->toBe(1)
        ->and($result->title)->toBe('Teste')
        ->and($result->price)->toBe(10.99)
        ->and($result->image)->toBe('https://google.com/');
});

it('sucessfully gets the product from Cache', function () {
    $fakestoreProductId = '1';
    $fakeStoreProductDto = new FakeStoreProductDto(
        id: $fakestoreProductId,
        title: 'Teste',
        price: 10.99,
        image: 'https://google.com/',
        rate: 4.5,
        ratingCount: 120,
    );

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.$fakestoreProductId", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $fakeStoreProductDto);

    $fakeStoreClientMock = Mockery::mock(FakeStoreClient::class);

    $fakeStoreClientMock->shouldNotReceive('getProductById');

    $productsRepositoryMock = Mockery::mock(ProductsRepository::class);
    $productsRepositoryMock->shouldNotReceive('updateOrCreateByFakeStoreId');

    $productsService = new ProductsService($productsRepositoryMock, $fakeStoreClientMock);
    $result = $productsService->getCachedProductByFakeStoreId($fakestoreProductId);

    expect($result)->toBeInstanceOf(FakeStoreProductDto::class)
        ->and($result->id)->toBe(1)
        ->and($result->title)->toBe('Teste')
        ->and($result->price)->toBe(10.99)
        ->and($result->image)->toBe('https://google.com/');
});

it('sucessfully gets the product from Database', function () {
    $fakestoreProductId = '1';

    Cache::shouldReceive('remember')
        ->once()
        ->with("products.fakestore_id.$fakestoreProductId", 3600, Mockery::type('Closure'))
        ->andReturnUsing(fn($key, $ttl, $closure) => $closure());

    $fakeStoreClientMock = Mockery::mock(FakeStoreClient::class);

    $fakeStoreClientMock->shouldReceive('getProductById')->andThrow(new ExternalApiException("External API error", 502, null));

    $productsRepositoryMock = Mockery::mock(ProductsRepository::class);
    $productsRepositoryMock->shouldNotReceive('updateOrCreateByFakeStoreId');
    $productsRepositoryMock->shouldReceive('findByFakestoreId')
        ->once()
        ->with($fakestoreProductId)
        ->andReturn(new Product([
            'fakestore_product_id' => $fakestoreProductId,
            'title' => 'Teste',
            'price_in_cents' => 1099,
            'image_url' => 'https://google.com/',
        ]));

    $productsService = new ProductsService($productsRepositoryMock, $fakeStoreClientMock);
    $result = $productsService->getCachedProductByFakeStoreId($fakestoreProductId);

    expect($result)->toBeInstanceOf(FakeStoreProductDto::class)
        ->and($result->id)->toBe(1)
        ->and($result->title)->toBe('Teste')
        ->and($result->price)->toBe(10.99)
        ->and($result->image)->toBe('https://google.com/');
});
