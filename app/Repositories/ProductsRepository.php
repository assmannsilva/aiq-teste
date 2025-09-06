<?php

namespace App\Repositories;

use App\Models\Product;

class ProductsRepository extends BaseRepository
{
    protected string $modelClass = Product::class;

    /**
     * Find a product by its FakeStore ID.
     * @param string $fakeStoreId
     * @return Product|null
     */
    public function findByFakestoreId(string $fakeStoreId): ?Product
    {
        return $this->createQueryBuilder()->firstWhere('fakestore_product_id', $fakeStoreId);
    }

    /**
     * Update or create a product by its FakeStore ID.
     * @param string $fakeStoreId
     * @param array $values
     * @return Product
     */
    public function updateOrCreateByFakeStoreId(string $fakeStoreId, array $values): Product
    {
        return $this->updateOrCreate(
            matchingAttributes: ['fakestore_product_id' => $fakeStoreId],
            values: $values
        );
    }
}
