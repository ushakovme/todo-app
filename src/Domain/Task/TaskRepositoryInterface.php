<?php
declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\Exception\TaskNotFoundException;

interface TaskRepositoryInterface
{
    /**
     * @throws TaskNotFoundException
     */
    public function findById(TaskId $id): Task;

    /**
     * @return Task[]
     */
    public function findAll(): iterable;

    /**
     * @return Task[]
     */
    public function findCompleted(): iterable;

    /**
     * @return Task[]
     */
    public function findNotCompleted(): iterable;

    public function save(Task $task);

    public function delete(Task $task);
}
