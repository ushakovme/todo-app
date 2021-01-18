<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Task\Exception\TaskContentEmptyException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Slim\Psr7\Factory\StreamFactory;
use Tests\TestCase;

class CreateTaskActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $content = 'content';
        $task = new Task(null, $content);

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->save($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/tasks');
        $factory = new StreamFactory();
        $request = $request->withBody($factory->createStream(json_encode([
            'content' => $content
        ])));

        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(201, $task);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testTaskContentEmptyException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, false, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $exception = new TaskContentEmptyException();

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/tasks');
        $request = $request->withParsedBody([]);

        $response = $app->handle($request);
        $payload = (string)$response->getBody();

        $expectedError = new ActionError(ActionError::BAD_REQUEST, $exception->getMessage());
        $expectedPayload = new ActionPayload(400, null, $expectedError);
        $serializedPayload =json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
