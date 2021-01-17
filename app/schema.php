<?php
declare(strict_types=1);

use App\Infrastructure\Persistence\CycleORM\SQLTaskRepository;
use App\Infrastructure\Persistence\CycleORM\TaskMapper;
use Cycle\ORM\Schema;
use Cycle\ORM\SchemaInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SchemaInterface::class => function () {
            return new Schema([
                App\Domain\Task\Task::class => [
                    Schema::ROLE => 'task',
                    Schema::ENTITY => App\Domain\Task\Task::class,
                    Schema::MAPPER => TaskMapper::class,
                    Schema::REPOSITORY => SQLTaskRepository::class,
                    Schema::DATABASE => 'default',
                    Schema::TABLE => 'tasks',
                    Schema::PRIMARY_KEY => 'id',
                    Schema::COLUMNS => [
                        'id' => 'id',
                        'content' => 'content',
                        'isCompleted' => 'completed',
                    ],
                    Schema::TYPECAST => [
                        'isCompleted' => 'bool'
                    ],
                    Schema::RELATIONS => []
                ]
            ]);
        }
    ]);
};
