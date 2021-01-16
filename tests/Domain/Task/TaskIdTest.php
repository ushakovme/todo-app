<?php
declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\Task\TaskId;
use Tests\TestCase;

class TaskIdTest extends TestCase
{
    public function testToInt()
    {
        $intId = 1;
        $id = TaskId::fromInt($intId);

        $this->assertEquals($intId, $id->toInt());
        $this->assertEquals((string)$intId, $id->toString());
    }
}
