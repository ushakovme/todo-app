<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Task;

use App\Application\Actions\ActionPayload;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use App\Domain\Task\TaskRepositoryInterface;
use DI\Container;
use Tests\TestCase;

class CompleteTaskActionTest extends TestCase
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

        $taskRepositoryProphecy
            ->save($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/tasks/' . (string)$taskId . '/complete');
        $response = $app->handle($request);

        $payload = (string)$response->getBody();
        $expectedPayload = new ActionPayload(200, $task);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
        $this->assertTrue($task->isCompleted());
    }
}
