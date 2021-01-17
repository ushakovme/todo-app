<?php
declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\Task\Exception\TaskContentEmptyException;
use App\Domain\Task\Exception\TaskContentLengthException;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function taskProvider()
    {
        return [
            [TaskId::fromInt(1), 'content1'],
            [TaskId::fromInt(2), 'content2'],
        ];
    }

    /**
     * @dataProvider taskProvider
     */
    public function testGetters(TaskId $id, string $content)
    {
        $task = new Task($id, $content);

        $this->assertEquals($id, $task->getId());
        $this->assertEquals($content, $task->getContent());
    }

    /**
     * @dataProvider taskProvider
     */
    public function testJsonSerialize(TaskId $id, string $content)
    {
        $task = new Task($id, $content);

        $expectedPayload = json_encode([
            'id' => $id,
            'content' => $content,
            'isCompleted' => $task->isCompleted(),
        ]);

        $this->assertEquals($expectedPayload, json_encode($task));
    }

    public function testContentLengthException()
    {
        $this->expectException(TaskContentLengthException::class);
        new Task(TaskId::fromInt(2), str_repeat(' ', 1002));
    }

    public function testContentEmptyException()
    {
        $this->expectException(TaskContentEmptyException::class);
        new Task(TaskId::fromInt(2), '');
    }
}
