<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
    public function getAllByUser(int $userId, ?string $status = null)
    {
        $query = $this->task->where('user_id', $userId);

        if ($status && in_array($status, ['pending', 'completed'])) {
            $query->where('status', $status);
        }

        return $query->latest()->get();
    }

    public function getById(int $id)
    {
        return $this->task->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->task->create($data);
    }

    public function update(int $id, array $data)
    {
        $task = $this->getById($id);
        $task->update($data);

        return $task;
    }
    public function delete(int $id)
    {
        return $this->getById($id)->delete();
    }
}
