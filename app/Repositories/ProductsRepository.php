<?php

namespace App\Repositories;

use App\Models\Product;

class ProductsRepository extends BaseRepository
{
    protected string $modelClass = Product::class;

    public function findByFakestoreId(string $fakeStoreId): ?Product
    {
        return $this->createQueryBuilder()->firstWhere('fakestore_product_id', $fakeStoreId);
    }

    public function updateOrCreateByFakeStoreId(string $fakeStoreId, array $values): Product
    {
        return $this->updateOrCreate(
            matchingAttributes: ['fakestore_product_id' => $fakeStoreId],
            values: $values
        );
    }
}
