<?php

namespace App\Application\Actions\Task;

use App\Domain\Task\TaskId;
use Psr\Http\Message\ResponseInterface;

class CompleteTaskAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $id = TaskId::fromInt((int)$this->resolveArg('id'));

        $task = $this->taskRepository->findById($id);

        $this->taskService->complete($task);

        return $this->respondWithData($task, 200);
    }
}
