<?php

namespace App\Services;

use App\Dtos\FakeStoreProductDto;
use App\Models\Client;
use App\Repositories\FavoriteRepository;

class FavoriteService
{
    const PER_PAGE_DEFAULT_PAGINATION = 15;

    public function __construct(
        private ProductsService $productsService,
        private FavoriteRepository $favoriteRepository
    ) {}

    /**
     * Get a list of client Favorites
     * @param Client $client
     * @param int $perPage
     * @return array {productDto: ProductDto}
     */
    public function listClientFavorites(Client $client, ?int $perPage): array
    {
        if (!$perPage) $perPage = self::PER_PAGE_DEFAULT_PAGINATION;

        $favorites = $this->favoriteRepository->getByClientId($client->id, self::PER_PAGE_DEFAULT_PAGINATION);
        $products = [];
        foreach ($favorites as $favorite) {
            $productDto = $this->productsService->getCachedProductByFakeStoreId($favorite->product_id);
            $products[] = $productDto;
        }
        return $products;
    }

    /**
     * Add a given Product ID as favorite to Client
     * @param Client $client
     * @param string $productId
     * @return FakeStoreProductDto
     */
    public function addFavoriteIntoClient(Client $client, string $productId): FakeStoreProductDto
    {
        $product = $this->productsService->getCachedProductByFakeStoreId($productId);
        $this->favoriteRepository->create([
            "product_id" => $product->id,
            "client_id" => $client->id
        ]);

        return $product;
    }
}
