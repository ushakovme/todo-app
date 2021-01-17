<?php

namespace App\Infrastructure\Persistence\CycleORM;

use App\Domain\AbstractId;
use App\Domain\Task\Task;
use App\Domain\Task\TaskId;
use Cycle\ORM\Mapper\Mapper;
use Cycle\ORM\ORMInterface;
use Laminas\Hydrator\Strategy\ClosureStrategy;

class TaskMapper extends Mapper
{
    public function __construct(ORMInterface $orm, string $role)
    {
        parent::__construct($orm, $role);
        $this->hydrator->addStrategy('id', new ClosureStrategy(function ($value, Task $task) {
            if (!$task->getId()) {
                return null;
            }

            return $task->getId()->toInt();
        }, function ($value, ?array $data = null) {
            return TaskId::fromInt($value);
        }));
    }
}
