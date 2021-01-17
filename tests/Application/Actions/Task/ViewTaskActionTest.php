<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskRepositoryInterface;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\TestCase;

class ViewTaskActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $taskId = TaskId::fromInt(1);
        $task = new Task($taskId, 'content');

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findById($taskId)
            ->willReturn($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/tasks/' . (string)$taskId);
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, $task);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testTaskNotFoundException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $taskId = TaskId::fromInt(1);

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $exception = new TaskNotFoundException();
        $taskRepositoryProphecy
            ->findById($taskId)
            ->willThrow($exception)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/tasks/' . (string)$taskId);
        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, $exception->getMessage());
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload =json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
