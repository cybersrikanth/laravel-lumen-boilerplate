<?php

namespace App\Repository\Eloquent;

use App\Repository\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseRepository implements BaseRepositoryInterface
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model
    {
        return $this->model->select($columns)->with($relations)->find($id);
    }

    public function findOnlyTrashedById(int $id): ?Model
    {
        return $this->model->onlyTrashed()->find($id);
    }

    public function get($where = [], array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->where($where)->get($columns);
    }

    public function has(...$where): bool
    {
        return $this->model->where(...$where)->count() > 0;
    }

    public function create(array $payload): ?Model
    {
        $model = $this->model->create($payload);
        return $model;
    }

    public function update(int $id, array $payload): bool
    {
        $model = $this->findById($id);
        return $model->update($payload);
    }

    public function deleteById(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function deleteManyById(array $ids): int
    {
        return $this->model->destroy($ids);
    }

    public function deleteMany($where = []): int
    {
        $ids = $this->get($where)->pluck('id')->toArray();
        return $this->deleteManyById($ids);
    }

    public function restoreById(int $id): bool
    {
        return $this->findOnlyTrashedById($id)->restore();
    }

    public function touch(int $id): bool
    {
        return $this->findById($id)->touch();
    }
}
