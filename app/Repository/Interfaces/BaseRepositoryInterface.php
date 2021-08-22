<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []): Collection;

    public function findById(int $id, array $columns = ['*'], array $relations = []): ?Model;

    public function findOnlyTrashedById(int $id): ?Model;

    public function get($where = [], array $columns = ['*'], array $relations = []): Collection;

    public function has(...$where): bool;

    public function create(array $payload): ?Model;

    public function update(int $id, array $payload): bool;

    public function deleteById(int $id): bool;

    public function deleteManyById(array $ids): int;

    public function deleteMany($where = []): int;

    public function restoreById(int $id): bool;

    public function touch(int $id): bool;
}
