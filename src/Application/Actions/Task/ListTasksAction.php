<?php

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface;

class ListTasksAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $queryParams = $this->request->getQueryParams();
        if (array_key_exists('completed', $queryParams)) {
            $showCompleted = (bool)$queryParams['completed'];

            if ($showCompleted) {
                $tasks = $this->taskRepository->findCompleted();
            } else {
                $tasks = $this->taskRepository->findNotCompleted();
            }
        } else {
            $tasks = $this->taskRepository->findAll();
        }

        return $this->respondWithData($tasks);
    }
}
