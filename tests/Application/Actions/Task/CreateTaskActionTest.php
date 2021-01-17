<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Actions\ActionPayload;
use App\Domain\Task\Task;
use App\Domain\Task\TaskRepositoryInterface;
use DI\Container;
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
        $request = $request->withParsedBody(['content' => $content]);

        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(201, $task);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
