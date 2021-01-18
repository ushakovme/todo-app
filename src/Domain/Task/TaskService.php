<?php
declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\Exception\TaskContentEmptyException;
use App\Domain\Task\Exception\TaskContentLengthException;

final class TaskService
{
    private TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function complete(Task $task)
    {
        $task->complete();
        $this->repository->save($task);
    }

    /**
     * @throws TaskContentEmptyException
     * @throws TaskContentLengthException
     */
    public function create(string $content): Task
    {
        $task = new Task(null, $content);
        $this->repository->save($task);

        return $task;
    }

    public function delete(Task $task)
    {
        $this->repository->delete($task);
    }
}
