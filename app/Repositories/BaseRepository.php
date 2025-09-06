<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected string $modelClass;

    protected function createQueryBuilder(): Builder
    {
        return \app($this->modelClass)->query();
    }

    public function getAll(?Builder $query, bool $paginate = false, ?int $perPage = null): Builder|PaginatorContract
    {
        if (!$query) $this->createQueryBuilder();
        if ($paginate) return $query->paginate($perPage);

        return $query->get();
    }

    public function findByPrimaryKey(string $id, bool $fail = false): ?Model
    {
        $query = $this->createQueryBuilder();
        if ($fail) return $query->findOrFail($id);

        return $query->find($id);
    }

    /**
     * saves a record
     * @param Model $model
     * @return Model $model
     */
    public function save(Model $model): Model
    {
        $model->save();
        return $model;
    }

    /**
     * creates a record
     * @param array $insertData
     * @return Model $model
     */
    public function create(array $insertData): Model
    {
        return $this->modelClass::create($insertData);
    }

    /**
     * updates a record
     * @param Model $model
     * @param array $updateData
     * @return Model $model
     */
    public function update(Model $model, array $updateData): Model
    {
        $model->update($updateData);
        return $model;
    }


    /**
     * Deletes a record
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}
