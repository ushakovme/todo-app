<?php

namespace App\Application\Actions\Task;

use App\Domain\Task\Task;
use Psr\Http\Message\ResponseInterface;

class CreateTaskAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $body = $this->request->getParsedBody();
        $content = $body['content'];

        $task = new Task(null, $content);

        $this->taskRepository->save($task);

        return $this->respondWithData($task, 201);
    }
}
