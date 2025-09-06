<?php

namespace App\Repositories;

use App\Models\Favorite;

class FavoriteRepository extends BaseRepository
{
    protected string $modelClass = Favorite::class;

    public function getByClientId(string $clientId, int $perPage)
    {
        $query = $this->createQueryBuilder();
        $query = $query->where("client_id", $clientId);
        return $this->getAll($query, \true, $perPage);
    }

    public function deleteByProductId(string $productId): bool
    {
        $query = $this->createQueryBuilder();
        return $query->where("product_id", $productId)->delete() > 0;
    }
}
