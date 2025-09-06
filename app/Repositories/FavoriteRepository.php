<?php

namespace App\Repositories;

use App\Models\Favorite;

class FavoriteRepository extends BaseRepository
{
    protected string $modelClass = Favorite::class;

    /**
     * Get favorites by client ID paginated.
     * @param string $clientId
     * @param int $perPage
     * @return PaginatorContract|Builder
     */
    public function getByClientId(string $clientId, int $perPage)
    {
        $query = $this->createQueryBuilder();
        $query = $query->where("client_id", $clientId);
        return $this->getAll($query, \true, $perPage);
    }

    /**
     * Delete favorite by product ID and client ID.
     * @param string $clientId
     * @param string $productId
     * @return bool
     */
    public function deleteByProductId(string $clientId, string $productId): bool
    {
        $query = $this->createQueryBuilder();
        return $query->where("product_id", $productId)->where("client_id", $clientId)->delete() > 0;
    }

    /**
     * Get favorite by product ID and client ID.
     * @param string $clientId
     * @param string $productId
     * @return Favorite|null
     */
    public function getByProductId(string $clientId, string $productId): ?Favorite
    {
        $query = $this->createQueryBuilder();
        return $query->where("product_id", $productId)->where("client_id", $clientId)->first();
    }
}
