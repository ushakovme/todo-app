<?php

namespace App\Application\Actions\Task;

use App\Domain\Task\Exception\TaskContentEmptyException;
use App\Domain\Task\Exception\TaskContentLengthException;
use App\Domain\Task\Task;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;

class CreateTaskAction extends AbstractTaskAction
{
    protected function action(): ResponseInterface
    {
        $body = $this->request->getParsedBody();
        $content = $body['content'] ?? '';

        try {
            $task = new Task(null, $content);
        } catch (TaskContentEmptyException $exception) {
            throw new HttpBadRequestException($this->request, $exception->getMessage());
        } catch (TaskContentLengthException $exception) {
            throw new HttpBadRequestException($this->request, $exception->getMessage());
        }

        $this->taskRepository->save($task);

        return $this->respondWithData($task, 201);
    }
}
