<?php

namespace App\Services;

use App\Dtos\FakeStoreProductDto;
use App\Models\Client;
use App\Repositories\FavoriteRepository;
use Illuminate\Pagination\Paginator;


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
     * @return Paginator
     */
    public function listClientFavorites(Client $client, ?int $perPage): Paginator
    {
        if (!$perPage) $perPage = self::PER_PAGE_DEFAULT_PAGINATION;

        $favorites = $this->favoriteRepository->getByClientId($client->id, self::PER_PAGE_DEFAULT_PAGINATION);
        $products = [];
        foreach ($favorites as $favorite) {
            $productDto = $this->productsService->getCachedProductByFakeStoreId($favorite->fake_store_product_id);
            $products[] = $productDto;
        }
        return new Paginator( //Paginator colocado aqui por conta dos Dtos
            $products,
            $perPage,
            $favorites->currentPage(),
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
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
            "fake_store_product_id" => $product->id,
            "client_id" => $client->id
        ]);

        return $product;
    }

    /**
     * Delete a given favorite by product ID
     * @param Client $client
     * @param string $productId
     * @return bool
     */
    public function deleteFavorite(Client $client, string $productId): bool //Essas podem ser consideradas como funcionalidades extras
    {
        return $this->favoriteRepository->deleteByProductId($client->id, $productId);
    }

    /**
     * Get a favorite product by product ID
     * @param Client $client
     * @param string $productId
     * @return ?FakeStoreProductDto
     */
    public function getFavoriteProduct(Client $client, string $productId): ?FakeStoreProductDto //Essas podem ser consideradas como funcionalidades extras
    {
        $favorite = $this->favoriteRepository->getByProductId($client->id, $productId);
        return $favorite ? $this->productsService->getCachedProductByFakeStoreId($favorite->fake_store_product_id) : null;
    }
}
