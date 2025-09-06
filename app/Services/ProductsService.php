<?php

namespace App\Services;

use App\Dtos\FakeStoreProductDto;
use App\Exceptions\ExternalApiException;
use App\External\FakeStoreClient;
use App\Repositories\ProductsRepository;
use Illuminate\Support\Facades\Cache;

// Implementei isso para que sempre retorne um produto para o cliente, se possível
// Tirei dados como rating e rating count, pois provavelmente estariam desatualizados e não são tão essenciais
// O único problema seria o do preço, mas creio que seja melhor mostrar o produto com o preço desatualizado do que
// mostrar uma mensagem de erro para o cliente
// imagem e titulo acho que também tem pouco problema se estiverem desatualizados

// A questão do preço resolvi usar o round mesmo porque o preço vem da API em duas casas decimais só
// Poderia ser usado uma biblioteca também, mas é overkill aqui
class ProductsService
{

    public function __construct(
        private ProductsRepository $productsRepository,
        private FakeStoreClient $fakeStoreClient
    ) {}

    /**
     * Fetch product data from Fakestore API and store/update data in local database.
     * @param string $fakestoreProductId
     * @throws ExternalApiException
     * @return FakeStoreProductDto
     */
    private function getProductData(string $fakestoreProductId): FakeStoreProductDto
    {
        try {
            $productDto = $this->fakeStoreClient->getProductById($fakestoreProductId);
            $this->updateOrCreateProductInLocalDatabase($productDto);
            return $productDto;
        } catch (ExternalApiException $e) {
            $product = $this->productsRepository->findByFakestoreId($fakestoreProductId);
            if (!$product) throw $e;

            return new FakeStoreProductDto(
                id: $product->fakestore_product_id,
                title: $product->title,
                price: round($product->price_in_cents / 100, 2),
                image: $product->image_url
            );
        }
    }

    /**
     * Store or update product data in local database
     * @param FakeStoreProductDto $productDto
     * @return void
     */
    private function updateOrCreateProductInLocalDatabase(FakeStoreProductDto $productDto): void
    {
        $this->productsRepository->updateOrCreateByFakeStoreId(
            (string) $productDto->id,
            [
                'title' => $productDto->title,
                'price_in_cents' => (int) round($productDto->price * 100),
                'image_url' => $productDto->image
            ]
        );
    }

    /**
     * Get product data from cache or fetch from Fakestore API if not cached
     * @param string $fakestoreProductId
     * @return FakeStoreProductDto
     */
    public function getCachedProductByFakeStoreId(string $fakestoreProductId)
    {
        $cacheKey = "products.fakestore_id." . $fakestoreProductId;
        return Cache::remember($cacheKey, 3600, function () use ($fakestoreProductId) {
            return $this->getProductData($fakestoreProductId);
        });
    }
}
