<?php

namespace App\Repositories;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Illuminate\Database\Eloquent\Model;

//Classe base feita para implementar as funções comuns que são usadas no Eloquent
// Feito dessa maneira para poder usar injeção de dependência e facilitar testes unitários
// Mas são as mesmas funções comuns do Facade do Eloquent
abstract class BaseRepository
{
    protected string $modelClass;

    /**
     * Create a new query builder instance for the model.
     * @return Builder
     */
    protected function createQueryBuilder(): Builder
    {
        return \app($this->modelClass)->query();
    }
    /**
     * Get all records, optionally paginated.
     * @param Builder|null $query
     * @param bool $paginate
     * @param int|null $perPage
     * @return Builder|PaginatorContract
     */
    public function getAll(?Builder $query, bool $paginate = false, ?int $perPage = null): Builder|PaginatorContract
    {
        if (!$query) $this->createQueryBuilder();
        if ($paginate) return $query->paginate($perPage);

        return $query->get();
    }

    /**
     * Find a record by its primary key.
     * @param string $id
     * @param bool $fail
     * @return Model|null
     */
    public function findByPrimaryKey(string $id, bool $fail = false): ?Model
    {
        $query = $this->createQueryBuilder();
        if ($fail) return $query->findOrFail($id);

        return $query->find($id);
    }

    /**
     * Updates a record or creates if not exists
     * @param array $matchingAttributes
     * @param array $values
     * @return Model 
     */
    public function updateOrCreate(array $matchingAttributes, array $values): Model
    {
        return $this->modelClass::updateOrCreate($matchingAttributes, $values);
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
