<?php

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface;

class ListTasksAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $tasks = $this->taskRepository->findAll();

        return $this->respondWithData($tasks);
    }
}
