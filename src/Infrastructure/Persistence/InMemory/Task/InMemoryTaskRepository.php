<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\ImMemory\Task;

use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskRepositoryInterface;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var Task[]
     */
    private array $tasks = [];

    /**
     * @param Task[]|null $tasks
     */
    public function __construct(?array $tasks = [])
    {
        $this->tasks = $tasks ?? [
                new Task(TaskId::fromInt(1), 'content 1'),
                new Task(TaskId::fromInt(2), 'content 2'),
            ];
    }


    public function findById(TaskId $id): Task
    {
        foreach ($this->tasks as $task) {
            if ($task->getId() === $id) {
                return $task;
            }
        }

        throw new TaskNotFoundException();
    }

    public function findAll(): array
    {
        return $this->tasks;
    }

    public function save(Task $task)
    {
        $this->tasks[] = $task;
    }

    public function findCompleted(): iterable
    {
        return array_filter($this->tasks, function (Task $task) {
            return $task->isCompleted();
        });
    }

    public function findNotCompleted(): iterable
    {
        return array_filter($this->tasks, function (Task $task) {
            return !$task->isCompleted();
        });
    }

    public function delete(Task $taskToRemove)
    {
        $this->tasks = array_filter($this->tasks, function (Task $task) use ($taskToRemove) {
            return $task->getId()->toInt() !== $taskToRemove->getId()->toInt();
        });
    }

}
