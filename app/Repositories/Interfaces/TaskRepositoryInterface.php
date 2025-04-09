<?php

namespace App\Repositories\Interfaces;

interface TaskRepositoryInterface
{

    public function getAllByUser(int $userId, ?string $status = null);
    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
