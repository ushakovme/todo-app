<?php

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface;

class ListTasksAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $showCompleted = (bool)($this->request->getQueryParams()['completed'] ?? false);

        if ($showCompleted) {
            $tasks = $this->taskRepository->findCompleted();
        } else {
            $tasks = $this->taskRepository->findAll();
        }

        return $this->respondWithData($tasks);
    }
}
